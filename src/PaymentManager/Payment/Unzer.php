<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\Payment;

use Carbon\Carbon;
use Pimcore\Bundle\EcommerceFrameworkBundle\Exception\UnsupportedException;
use Pimcore\Bundle\EcommerceFrameworkBundle\Exception\UnzerPaymentProviderException;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\EcommerceFrameworkBundle\OrderManager\OrderAgentInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\Status;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\StatusInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\V7\Payment\StartPaymentRequest\AbstractRequest;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\V7\Payment\StartPaymentResponse\StartPaymentResponseInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\V7\Payment\StartPaymentResponse\UrlResponse;
use Pimcore\Bundle\EcommerceFrameworkBundle\PriceSystem\PriceInterface;
use Pimcore\Localization\LocaleService;
use Pimcore\Model\DataObject\Objectbrick\Data\PaymentProviderUnzer;
use Pimcore\Model\DataObject\OnlineShopOrder;
use UnzerSDK\Exceptions\UnzerApiException;
use UnzerSDK\Resources\CustomerFactory;
use UnzerSDK\Resources\EmbeddedResources\Address;
use UnzerSDK\Resources\Payment;
use UnzerSDK\Resources\TransactionTypes\Cancellation;
use UnzerSDK\Resources\TransactionTypes\Charge;

class Unzer extends AbstractPayment
{
    protected string $privateAccessKey;

    protected string $publicAccessKey;

    protected array $authorizedData = [];

    public function __construct(array $options)
    {
        if (empty($options['privateAccessKey'])) {
            throw new \InvalidArgumentException('no private access key given');
        }

        $this->privateAccessKey = $options['privateAccessKey'];

        if (empty($options['publicAccessKey'])) {
            throw new \InvalidArgumentException('no private access key given');
        }

        $this->publicAccessKey = $options['publicAccessKey'];
    }

    public function getName(): string
    {
        return 'Unzer';
    }

    public function getPublicAccessKey(): string
    {
        return $this->publicAccessKey;
    }

    public function initPayment(PriceInterface $price, array $config): void
    {
        throw new UnsupportedException('use startPayment instead as initPayment() is deprecated and the order agent is needed by the Unzer payment provider');
    }

    public function startPayment(OrderAgentInterface $orderAgent, PriceInterface $price, AbstractRequest $config): StartPaymentResponseInterface
    {
        if (empty($config['paymentReference'])) {
            throw new \InvalidArgumentException('no paymentReference sent');
        }

        if (empty($config['internalPaymentId'])) {
            throw new \InvalidArgumentException('no internalPaymentId sent');
        }

        if (empty($config['returnUrl'])) {
            throw new \InvalidArgumentException('no return sent');
        }

        if (empty($config['errorUrl'])) {
            throw new \InvalidArgumentException('no errorUrl sent');
        }

        $order = $orderAgent->getOrder();

        $unzer = new \UnzerSDK\Unzer($this->privateAccessKey, \Pimcore::getKernel()?->getContainer()->get(LocaleService::class)?->getLocale());

        $billingAddress = (new Address())
                          ->setName($order->getCustomerLastname() . ' ' . $order->getCustomerLastname())
                          ->setStreet($order->getCustomerStreet())
                          ->setZip($order->getCustomerZip())
                          ->setCity($order->getCustomerCity())
                          ->setCountry($order->getCustomerCountry());

        // check if alternative shipping address is available
        if ($order->getDeliveryLastname()) {
            $shippingAddress = (new Address())
                ->setName($order->getDeliveryFirstname() . ' ' . $order->getDeliveryLastname())
                ->setStreet($order->getDeliveryStreet())
                ->setZip($order->getDeliveryZip())
                ->setCity($order->getDeliveryCity())
                ->setCountry($order->getDeliveryCountry());
        } else {
            $shippingAddress = $billingAddress;
        }

        $customer = (CustomerFactory::createCustomer($order->getCustomerFirstname(), $order->getCustomerLastname()))
                    ->setEmail($order->getCustomerEmail())
                    ->setBillingAddress($billingAddress)
                    ->setShippingAddress($shippingAddress);

        // a customerBirthdate attribute is needed if invoice should be used as payment method
        if (method_exists($order, 'getCustomerBirthdate') && $birthdate = $order->getCustomerBirthdate()) {
            /** @var Carbon $birthdate */
            $customer->setBirthDate($birthdate->format('Y-m-d'));
        }

        try {
            $charge = new Charge(
                (float) $price->getAmount()->asString(2),
                $price->getCurrency()->getShortName(),
                $config['returnUrl']
            );
            $charge->setOrderId($this->transformInternalPaymentId((string) $config['internalPaymentId']));

            $transaction = $unzer->performCharge(
                $charge,
                $config['paymentReference'],
                $customer
            );

            $transaction->getPaymentId();

            $orderAgent = Factory::getInstance()->getOrderManager()->createOrderAgent($order);

            $paymentStatus = new Status(
                $config['internalPaymentId'],
                $transaction->getPaymentId(),
                '',
                StatusInterface::STATUS_PENDING,
                [
                    'unzer_amount' => $transaction->getPayment()?->getAmount()->getCharged(),
                    'unzer_currency' => $transaction->getPayment()?->getCurrency(),
                    'unzer_paymentType' => $transaction->getPayment()?->getPaymentType()?->jsonSerialize(),
                    'unzer_paymentReference' => $config['paymentReference'],
                    'unzer_responseStatus' => '',
                    'unzer_response' => $transaction->jsonSerialize(),
                ]
            );
            $orderAgent->updatePayment($paymentStatus);

            if (empty($transaction->getRedirectUrl()) && $transaction->isSuccess()) {
                $url = $config['returnUrl'];
            } elseif ($transaction->isSuccess()) {
                $url = $transaction->getRedirectUrl();
            } elseif (!empty($transaction->getRedirectUrl()) && $transaction->isPending()) {
                $url = $transaction->getRedirectUrl();
            } else {
                $url = $config['returnUrl'];
            }
        } catch (UnzerApiException $exception) {
            $url = $this->generateErrorUrl($config['errorUrl'], $exception->getMerchantMessage(), $exception->getClientMessage());
        } catch (\Exception $exception) {
            $url = $this->generateErrorUrl($config['errorUrl'], $exception->getMessage());
        }

        return new UrlResponse($orderAgent->getOrder(), $url);
    }

    protected function transformInternalPaymentId(string $internalPaymentId): string
    {
        return str_replace('~', '---', $internalPaymentId);
    }

    protected function generateErrorUrl($errorUrl, $merchantMessage, $clientMessage = ''): string
    {
        $errorUrl .= !str_contains($errorUrl, '?') ? '?' : '&';

        return $errorUrl . 'merchantMessage=' . urlencode($merchantMessage) . '&clientMessage=' . urlencode($clientMessage);
    }

    public function handleResponse($response): StatusInterface
    {
        $order = $response['order'];
        if (!$order instanceof OnlineShopOrder) {
            throw new \InvalidArgumentException('no order sent');
        }

        $clientMessage = '';
        $payment = null;
        $paymentInfo = null;

        try {
            $orderAgent = Factory::getInstance()->getOrderManager()->createOrderAgent($order);
            $paymentInfo = $orderAgent->getCurrentPendingPaymentInfo();
            $payment = $this->fetchPayment($order);

            if (!$paymentInfo) {
                return new Status('', '', 'not found', '');
            }

            if ($payment->isCompleted()) {
                $this->setAuthorizedData([
                        'amount' => $payment->getAmount()->getCharged(),
                        'currency' => $payment->getCurrency(),
                        'paymentType' => $payment->getPaymentType()?->jsonSerialize(),
                        'paymentReference' => $paymentInfo->getPaymentReference(),
                        'paymentMethod' => $this->getPaymentTypeClass($payment),
                        'clientMessage' => '',
                        'merchantMessage' => '',
                        'chargeId' => $payment->getChargeByIndex(0)?->getId(),
                ]);

                return new Status(
                    $paymentInfo->getInternalPaymentId(),
                    $payment->getId(),
                    '',
                    StatusInterface::STATUS_AUTHORIZED,
                    [
                        'unzer_amount' => $payment->getAmount()->getCharged(),
                        'unzer_currency' => $payment->getCurrency(),
                        'unzer_paymentType' => $payment->getPaymentType()?->jsonSerialize(),
                        'unzer_paymentReference' => $paymentInfo->getPaymentReference(),
                        'unzer_paymentMethod' => $this->getPaymentTypeClass($payment),
                        'unzer_responseStatus' => 'completed',
                        'unzer_response' => $payment->jsonSerialize(),
                    ]
                );
            }

            if ($payment->isPending()) {
                return new Status(
                    $paymentInfo->getInternalPaymentId(),
                    $payment->getId(),
                    '',
                    StatusInterface::STATUS_PENDING,
                    [
                        'unzer_amount' => $payment->getAmount()->getCharged(),
                        'unzer_currency' => $payment->getCurrency(),
                        'unzer_paymentType' => $payment->getPaymentType()?->jsonSerialize(),
                        'unzer_paymentReference' => $paymentInfo->getPaymentReference(),
                        'unzer_paymentMethod' => $this->getPaymentTypeClass($payment),
                        'unzer_responseStatus' => 'pending',
                        'unzer_response' => $payment->jsonSerialize(),
                    ]
                );
            }

            // Check the result message of the transaction to find out what went wrong.
            $transaction = $payment->getChargeByIndex(0);
            if ($transaction instanceof Charge) {
                $merchantMessage = $transaction->getMessage()->getCustomer();
            } else {
                $merchantMessage = 'State: '. $payment->getStateName();
            }
        } catch (UnzerApiException $e) {
            $clientMessage = $e->getClientMessage();
            $merchantMessage = $e->getMerchantMessage();
        } catch (\Throwable $e) {
            $merchantMessage = $e->getMessage();
        }

        $this->setAuthorizedData([
            'amount' => $payment ? $payment->getAmount()->getCharged() : '',
            'currency' => $payment ? $payment->getCurrency() : '',
            'paymentType' => $payment ? $payment->getPaymentType()?->jsonSerialize() : '',
            'paymentMethod' => $this->getPaymentTypeClass($payment),
            'paymentReference' => $paymentInfo ? $paymentInfo->getPaymentReference() : '',
            'clientMessage' => $clientMessage,
            'merchantMessage' => $merchantMessage,
        ]);

        return new Status(
            $paymentInfo ? $paymentInfo->getInternalPaymentId() : '',
            $payment ? $payment->getId() : '',
            '',
            StatusInterface::STATUS_CANCELLED,
            [
                'unzer_amount' => $payment ? $payment->getAmount()->getCharged() : '',
                'unzer_currency' => $payment ? $payment->getCurrency() : '',
                'unzer_paymentType' => $payment ? $payment->getPaymentType()?->jsonSerialize() : '',
                'unzer_paymentReference' => $paymentInfo ? $paymentInfo->getPaymentReference() : '',
                'unzer_paymentMethod' => $this->getPaymentTypeClass($payment),
                'unzer_clientMessage' => $clientMessage,
                'unzer_merchantMessage' => $merchantMessage,
                'unzer_responseStatus' => 'error',
                'unzer_response' => $payment->jsonSerialize(),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getAuthorizedData(): array
    {
        return $this->authorizedData;
    }

    /**
     * @inheritdoc
     */
    public function setAuthorizedData(array $authorizedData): void
    {
        $this->authorizedData = $authorizedData;
    }

    /**
     * @throws UnzerPaymentProviderException
     */
    public function executeDebit(?PriceInterface $price = null, ?string $reference = null): StatusInterface
    {
        throw new UnzerPaymentProviderException('not implemented yet');
    }

    /**
     * @throws UnzerPaymentProviderException
     */
    public function executeCredit(PriceInterface $price, string $reference, string $transactionId): StatusInterface
    {
        throw new UnzerPaymentProviderException('not implemented yet');
    }

    /**
     * @param OnlineShopOrder $order
     * @param PriceInterface $price
     *
     * @return bool
     *
     * @throws UnzerApiException
     */
    public function cancelCharge(OnlineShopOrder $order, PriceInterface $price): bool
    {
        $unzer = new \UnzerSDK\Unzer($this->privateAccessKey);
        $unzerBrick = $order->getPaymentProvider()?->getPaymentProviderUnzer();

        if ($unzerBrick instanceof PaymentProviderUnzer) {
            $result = $unzer->cancelChargeById(
                $unzerBrick->getAuth_paymentReference(),
                $unzerBrick->getAuth_chargeId(),
                $price->getAmount()->asNumeric()
            );

            return $result->isSuccess();
        }

        return false;
    }

    /**
     * @param OnlineShopOrder $order
     *
     * @return float|int
     *
     * @throws UnzerApiException
     */
    public function getMaxCancelAmount(OnlineShopOrder $order): float|int
    {
        $unzer = new \UnzerSDK\Unzer($this->privateAccessKey);
        $unzerBrick = $order->getPaymentProvider()?->getPaymentProviderUnzer();

        if ($unzerBrick instanceof PaymentProviderUnzer) {
            $charge = $unzer->fetchChargeById($unzerBrick->getAuth_paymentReference(), $unzerBrick->getAuth_chargeId());
            $totalAmount = $charge->getAmount();

            /** @var Cancellation $cancellation */
            foreach ($charge->getCancellations() as $cancellation) {
                $totalAmount -= $cancellation->getAmount();
            }

            return $totalAmount;
        }

        return 0;
    }

    /**
     * @param OnlineShopOrder $order
     *
     * @return ?Payment
     *
     * @throws UnzerApiException
     */
    public function fetchPayment(OnlineShopOrder $order): ?Payment
    {
        $orderAgent = Factory::getInstance()->getOrderManager()->createOrderAgent($order);
        $paymentInfo = $orderAgent->getCurrentPendingPaymentInfo();

        if (!$paymentInfo) {
            return null;
        }

        if (empty($paymentInfo->getPaymentReference())) {
            return null;
        }

        return (new \UnzerSDK\Unzer($this->privateAccessKey))->fetchPayment($paymentInfo->getPaymentReference());
    }

    protected function getPaymentTypeClass(?Payment $payment): string
    {
        return $payment && $payment->getPaymentType() !== null ? get_class($payment->getPaymentType()) : '';
    }
}

class_alias(Unzer::class, 'Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\Payment\Heidelpay');
