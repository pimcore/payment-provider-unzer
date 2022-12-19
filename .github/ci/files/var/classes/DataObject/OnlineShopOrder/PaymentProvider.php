<?php

namespace Pimcore\Model\DataObject\OnlineShopOrder;

use Pimcore\Model\DataObject\Exception\InheritanceParentNotFoundException;

class PaymentProvider extends \Pimcore\Model\DataObject\Objectbrick {

    protected $brickGetters = ['PaymentProviderUnzer'];

    protected $PaymentProviderUnzer = null;

    /**
    * @return \Pimcore\Model\DataObject\Objectbrick\Data\PaymentProviderUnzer|null
    */
    public function getPaymentProviderUnzer(bool $includeDeletedBricks = false)
    {
        if(!$includeDeletedBricks &&
            isset($this->PaymentProviderUnzer) &&
            $this->PaymentProviderUnzer->getDoDelete()) {
                return null;
        }
        return $this->PaymentProviderUnzer;
    }

    /**
    * @param \Pimcore\Model\DataObject\Objectbrick\Data\PaymentProviderUnzer $PaymentProviderUnzer
    * @return $this
    */
    public function setPaymentProviderUnzer(\Pimcore\Model\DataObject\Objectbrick\Data\PaymentProviderUnzer $PaymentProviderUnzer): static
    {
        $this->PaymentProviderUnzer = $PaymentProviderUnzer;
        return $this;
    }

}

