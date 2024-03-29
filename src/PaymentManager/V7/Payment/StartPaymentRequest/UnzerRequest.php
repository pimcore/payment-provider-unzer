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

namespace Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\V7\Payment\StartPaymentRequest;

class UnzerRequest extends AbstractRequest
{
    protected string|null $paymentReference = null;
    protected string|null $internalPaymentId = null;
    protected string $returnUrl;
    protected string $errorUrl;

    public function getPaymentReference(): ?string
    {
        return $this->paymentReference;
    }

    public function setPaymentReference(?string $paymentReference): void
    {
        $this->paymentReference = $paymentReference;
    }

    public function getInternalPaymentId(): ?string
    {
        return $this->internalPaymentId;
    }

    public function setInternalPaymentId(?string $internalPaymentId): void
    {
        $this->internalPaymentId = $internalPaymentId;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    public function getErrorUrl(): string
    {
        return $this->errorUrl;
    }

    public function setErrorUrl(string $errorUrl): void
    {
        $this->errorUrl = $errorUrl;
    }
}

class_alias(UnzerRequest::class, 'Pimcore\Bundle\EcommerceFrameworkBundle\PaymentManager\V7\Payment\StartPaymentRequest\HeidelpayRequest');
