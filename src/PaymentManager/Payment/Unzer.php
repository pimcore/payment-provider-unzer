<?php

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
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\EcommerceFrameworkBundle\OrderManager\OrderAgentInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\Status;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\StatusInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\V7\Payment\PaymentInterface;
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

class Unzer extends AbstractPayment implements PaymentInterface
{
    /**
     * @var string
     */
    protected $privateAccessKey;

    /**
     * @var string
     */
    protected $publicAccessKey;

    /**
     * @var array
     */
    protected $authorizedData = [];

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

    public function getName()
    {
        return 'Unzer';
    }

    /**
     * @return string
     */
    public function getPublicAccessKey(): string
    {
        return $this->publicAccessKey;
    }

    public function initPayment(PriceInterface $price, array $config)
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

        $unzer = new \UnzerSDK\Unzer($this->privateAccessKey, \Pimcore::getKernel()->getContainer()->get(LocaleService::class)->getLocale());

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
        if (method_exists($order, 'getCustomerBirthdate')) {
            if ($birthdate = $order->getCustomerBirthdate()) {
                /** @var Carbon $birthdate */
                $customer->setBirthDate($birthdate->format('Y-m-d'));
            }
        }

        $url = null;
        try {
            $transaction = $unzer->charge(
                (float) $price->getAmount()->asString(2),
                $price->getCurrency()->getShortName(),
                $config['paymentReference'],
                $config['returnUrl'],
                $customer,
                $this->transformInternalPaymentId($config['internalPaymentId'])
            );

            $transaction->getPaymentId();

            $orderAgent = Factory::getInstance()->getOrderManager()->createOrderAgent($order);

            $paymentStatus = new Status(
                $config['internalPaymentId'],
                $transaction->getPaymentId(),
                '',
                StatusInterface::STATUS_PENDING,
                [
                    'unzer_amount' => $transaction->getPayment()->getAmount()->getCharged(),
                    'unzer_currency' => $transaction->getPayment()->getCurrency(),
                    'unzer_paymentType' => $transaction->getPayment()->getPaymentType()->jsonSerialize(),
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

    protected function transformInternalPaymentId($internalPaymentId)
    {
        return str_replace('~', '---', $internalPaymentId);
    }

    protected function generateErrorUrl($errorUrl, $merchantMessage, $clientMessage = '')
    {
        $errorUrl .= strpos($errorUrl, '?') === false ? '?' : '&';

        return $errorUrl . 'merchantMessage=' . urlencode($merchantMessage) . '&clientMessage=' . urlencode($clientMessage);
    }

    public function handleResponse($response)
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
                        'paymentType' => $payment->getPaymentType()->jsonSerialize(),
                        'paymentReference' => $paymentInfo->getPaymentReference(),
                        'paymentMethod' => get_class($payment->getPaymentType()),
                        'clientMessage' => '',
                        'merchantMessage' => '',
                        'chargeId' => $payment->getChargeByIndex(0)->getId(),
                ]);

                return new Status(
                    $paymentInfo->getInternalPaymentId(),
                    $payment->getId(),
                    '',
                    StatusInterface::STATUS_AUTHORIZED,
                    [
                        'unzer_amount' => $payment->getAmount()->getCharged(),
                        'unzer_currency' => $payment->getCurrency(),
                        'unzer_paymentType' => $payment->getPaymentType()->jsonSerialize(),
                        'unzer_paymentReference' => $paymentInfo->getPaymentReference(),
                        'unzer_paymentMethod' => get_class($payment->getPaymentType()),
                        'unzer_responseStatus' => 'completed',
                        'unzer_response' => $payment->jsonSerialize(),
                    ]
                );
            } elseif ($payment->isPending()) {
                return new Status(
                    $paymentInfo->getInternalPaymentId(),
                    $payment->getId(),
                    '',
                    StatusInterface::STATUS_PENDING,
                    [
                        'unzer_amount' => $payment->getAmount()->getCharged(),
                        'unzer_currency' => $payment->getCurrency(),
                        'unzer_paymentType' => $payment->getPaymentType()->jsonSerialize(),
                        'unzer_paymentReference' => $paymentInfo->getPaymentReference(),
                        'unzer_paymentMethod' => get_class($payment->getPaymentType()),
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
                $merchantMessage = "State: ". $payment->getStateName();
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
            'paymentType' => $payment ? $payment->getPaymentType()->jsonSerialize() : '',
            'paymentMethod' => $payment ? get_class($payment->getPaymentType()) : '',
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
                'unzer_paymentType' => $payment ? $payment->getPaymentType()->jsonSerialize() : '',
                'unzer_paymentReference' => $paymentInfo ? $paymentInfo->getPaymentReference() : '',
                'unzer_paymentMethod' => $payment ? get_class($payment->getPaymentType()) : '',
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
    public function getAuthorizedData()
    {
        return $this->authorizedData;
    }

    /**
     * @inheritdoc
     */
    public function setAuthorizedData(array $authorizedData)
    {
        $this->authorizedData = $authorizedData;
    }

    public function executeDebit(PriceInterface $price = null, $reference = null)
    {
        throw new \Exception('not implemented yet');
    }

    public function executeCredit(PriceInterface $price, $reference, $transactionId)
    {
        throw new \Exception('not implemented yet');
    }

    /**
     * @param OnlineShopOrder $order
     * @param PriceInterface $price
     *
     * @return bool
     *
     * @throws UnzerApiException
     */
    public function cancelCharge(OnlineShopOrder $order, PriceInterface $price)
    {
        $unzer = new \UnzerSDK\Unzer($this->privateAccessKey);
        $unzerBrick = $order->getPaymentProvider()->getPaymentProviderUnzer();

        if ($unzerBrick instanceof PaymentProviderUnzer) {
            $result = $unzer->cancelChargeById($unzerBrick->getAuth_paymentReference(), $unzerBrick->getAuth_chargeId(), $price->getAmount()->asNumeric());

            return $result->isSuccess();
        }

        return false;
    }

    /**
     * @param OnlineShopOrder $order
     *
     * @return float
     *
     * @throws UnzerApiException
     */
    public function getMaxCancelAmount(OnlineShopOrder $order)
    {
        $unzer = new \UnzerSDK\Unzer($this->privateAccessKey);
        $unzerBrick = $order->getPaymentProvider()->getPaymentProviderUnzer();

        if ($unzerBrick instanceof PaymentProviderUnzer) {
            $charge = $unzer->fetchChargeById($unzerBrick->getAuth_paymentReference(), $unzerBrick->getAuth_chargeId());
            $totalAmount = $charge->getAmount();

            /**
             * @var Cancellation $cancellation
             */
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

        $unzer = new \UnzerSDK\Unzer($this->privateAccessKey);

        return $unzer->fetchPayment($paymentInfo->getPaymentReference());
    }
}

class_alias(Unzer::class, 'Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\Payment\Heidelpay');
