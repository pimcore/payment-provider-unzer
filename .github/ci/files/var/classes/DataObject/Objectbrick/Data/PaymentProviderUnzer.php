<?php

/**
 * Fields Summary:
 * - auth_paymentMethod [input]
 * - auth_amount [input]
 * - auth_currency [input]
 * - auth_paymentType [input]
 * - auth_paymentReference [input]
 * - auth_clientMessage [input]
 * - auth_merchantMessage [input]
 * - auth_chargeId [input]
 */

namespace Pimcore\Model\DataObject\Objectbrick\Data;

use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\Exception\InheritanceParentNotFoundException;
use Pimcore\Model\DataObject\PreGetValueHookInterface;


class PaymentProviderUnzer extends DataObject\Objectbrick\Data\AbstractData
{
public const FIELD_AUTH_PAYMENT_METHOD = 'auth_paymentMethod';
public const FIELD_AUTH_AMOUNT = 'auth_amount';
public const FIELD_AUTH_CURRENCY = 'auth_currency';
public const FIELD_AUTH_PAYMENT_TYPE = 'auth_paymentType';
public const FIELD_AUTH_PAYMENT_REFERENCE = 'auth_paymentReference';
public const FIELD_AUTH_CLIENT_MESSAGE = 'auth_clientMessage';
public const FIELD_AUTH_MERCHANT_MESSAGE = 'auth_merchantMessage';
public const FIELD_AUTH_CHARGE_ID = 'auth_chargeId';

protected string $type = "PaymentProviderUnzer";
protected $auth_paymentMethod;
protected $auth_amount;
protected $auth_currency;
protected $auth_paymentType;
protected $auth_paymentReference;
protected $auth_clientMessage;
protected $auth_merchantMessage;
protected $auth_chargeId;


/**
* PaymentProviderUnzer constructor.
* @param DataObject\Concrete $object
*/
public function __construct(DataObject\Concrete $object)
{
	parent::__construct($object);
	$this->markFieldDirty("_self");
}


/**
* Get auth_paymentMethod - Payment Method
* @return string|null
*/
public function getAuth_paymentMethod(): ?string
{
	$data = $this->auth_paymentMethod;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_paymentMethod")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_paymentMethod");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_paymentMethod - Payment Method
* @param string|null $auth_paymentMethod
* @return $this
*/
public function setAuth_paymentMethod (?string $auth_paymentMethod): static
{
	$this->auth_paymentMethod = $auth_paymentMethod;

	return $this;
}

/**
* Get auth_amount - Amount
* @return string|null
*/
public function getAuth_amount(): ?string
{
	$data = $this->auth_amount;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_amount")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_amount");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_amount - Amount
* @param string|null $auth_amount
* @return $this
*/
public function setAuth_amount (?string $auth_amount): static
{
	$this->auth_amount = $auth_amount;

	return $this;
}

/**
* Get auth_currency - Currency
* @return string|null
*/
public function getAuth_currency(): ?string
{
	$data = $this->auth_currency;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_currency")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_currency");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_currency - Currency
* @param string|null $auth_currency
* @return $this
*/
public function setAuth_currency (?string $auth_currency): static
{
	$this->auth_currency = $auth_currency;

	return $this;
}

/**
* Get auth_paymentType - Payment Type
* @return string|null
*/
public function getAuth_paymentType(): ?string
{
	$data = $this->auth_paymentType;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_paymentType")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_paymentType");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_paymentType - Payment Type
* @param string|null $auth_paymentType
* @return $this
*/
public function setAuth_paymentType (?string $auth_paymentType): static
{
	$this->auth_paymentType = $auth_paymentType;

	return $this;
}

/**
* Get auth_paymentReference - Payment Reference
* @return string|null
*/
public function getAuth_paymentReference(): ?string
{
	$data = $this->auth_paymentReference;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_paymentReference")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_paymentReference");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_paymentReference - Payment Reference
* @param string|null $auth_paymentReference
* @return $this
*/
public function setAuth_paymentReference (?string $auth_paymentReference): static
{
	$this->auth_paymentReference = $auth_paymentReference;

	return $this;
}

/**
* Get auth_clientMessage - Client Message
* @return string|null
*/
public function getAuth_clientMessage(): ?string
{
	$data = $this->auth_clientMessage;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_clientMessage")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_clientMessage");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_clientMessage - Client Message
* @param string|null $auth_clientMessage
* @return $this
*/
public function setAuth_clientMessage (?string $auth_clientMessage): static
{
	$this->auth_clientMessage = $auth_clientMessage;

	return $this;
}

/**
* Get auth_merchantMessage - Merchant Message
* @return string|null
*/
public function getAuth_merchantMessage(): ?string
{
	$data = $this->auth_merchantMessage;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_merchantMessage")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_merchantMessage");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_merchantMessage - Merchant Message
* @param string|null $auth_merchantMessage
* @return $this
*/
public function setAuth_merchantMessage (?string $auth_merchantMessage): static
{
	$this->auth_merchantMessage = $auth_merchantMessage;

	return $this;
}

/**
* Get auth_chargeId - Charge ID
* @return string|null
*/
public function getAuth_chargeId(): ?string
{
	$data = $this->auth_chargeId;
	if(\Pimcore\Model\DataObject::doGetInheritedValues($this->getObject()) && $this->getDefinition()->getFieldDefinition("auth_chargeId")->isEmpty($data)) {
		try {
			return $this->getValueFromParent("auth_chargeId");
		} catch (InheritanceParentNotFoundException $e) {
			// no data from parent available, continue ...
		}
	}
	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set auth_chargeId - Charge ID
* @param string|null $auth_chargeId
* @return $this
*/
public function setAuth_chargeId (?string $auth_chargeId): static
{
	$this->auth_chargeId = $auth_chargeId;

	return $this;
}

}

