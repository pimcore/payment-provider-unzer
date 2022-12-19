<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - active [checkbox]
 * - gender [gender]
 * - firstname [firstname]
 * - lastname [lastname]
 * - company [input]
 * - email [email]
 * - street [input]
 * - zip [input]
 * - city [input]
 * - countryCode [country]
 * - phone [input]
 * - idEncoded [input]
 * - customerLanguage [language]
 * - newsletter [consent]
 * - newsletterConfirmed [newsletterConfirmed]
 * - newsletterConfirmToken [input]
 * - profiling [consent]
 * - manualSegments [advancedManyToManyObjectRelation]
 * - calculatedSegments [advancedManyToManyObjectRelation]
 * - password [password]
 * - ssoIdentities [manyToManyObjectRelation]
 * - passwordRecoveryToken [input]
 * - passwordRecoveryTokenDate [datetime]
 */

namespace Pimcore\Model\DataObject;

use Pimcore\Model\DataObject\Exception\InheritanceParentNotFoundException;
use Pimcore\Model\DataObject\PreGetValueHookInterface;

/**
* @method static \Pimcore\Model\DataObject\Customer\Listing getList(array $config = [])
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByActive(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByGender(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByFirstname(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByLastname(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByCompany(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByEmail(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByStreet(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByZip(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByCity(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByCountryCode(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByPhone(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByIdEncoded(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByCustomerLanguage(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByNewsletterConfirmed(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByNewsletterConfirmToken(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByManualSegments(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByCalculatedSegments(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getBySsoIdentities(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByPasswordRecoveryToken(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
* @method static \Pimcore\Model\DataObject\Customer\Listing|\Pimcore\Model\DataObject\Customer|null getByPasswordRecoveryTokenDate(mixed $value, ?int $limit = null, int $offset = 0, ?array $objectTypes = null)
*/

class Customer extends \CustomerManagementFrameworkBundle\Model\AbstractCustomer\DefaultAbstractUserawareCustomer
{
public const FIELD_ACTIVE = 'active';
public const FIELD_GENDER = 'gender';
public const FIELD_FIRSTNAME = 'firstname';
public const FIELD_LASTNAME = 'lastname';
public const FIELD_COMPANY = 'company';
public const FIELD_EMAIL = 'email';
public const FIELD_STREET = 'street';
public const FIELD_ZIP = 'zip';
public const FIELD_CITY = 'city';
public const FIELD_COUNTRY_CODE = 'countryCode';
public const FIELD_PHONE = 'phone';
public const FIELD_ID_ENCODED = 'idEncoded';
public const FIELD_CUSTOMER_LANGUAGE = 'customerLanguage';
public const FIELD_NEWSLETTER = 'newsletter';
public const FIELD_NEWSLETTER_CONFIRMED = 'newsletterConfirmed';
public const FIELD_NEWSLETTER_CONFIRM_TOKEN = 'newsletterConfirmToken';
public const FIELD_PROFILING = 'profiling';
public const FIELD_MANUAL_SEGMENTS = 'manualSegments';
public const FIELD_CALCULATED_SEGMENTS = 'calculatedSegments';
public const FIELD_PASSWORD = 'password';
public const FIELD_SSO_IDENTITIES = 'ssoIdentities';
public const FIELD_PASSWORD_RECOVERY_TOKEN = 'passwordRecoveryToken';
public const FIELD_PASSWORD_RECOVERY_TOKEN_DATE = 'passwordRecoveryTokenDate';

protected $classId = "CU";
protected $className = "Customer";
protected $active;
protected $gender;
protected $firstname;
protected $lastname;
protected $company;
protected $email;
protected $street;
protected $zip;
protected $city;
protected $countryCode;
protected $phone;
protected $idEncoded;
protected $customerLanguage;
protected $newsletter;
protected $newsletterConfirmed;
protected $newsletterConfirmToken;
protected $profiling;
protected $manualSegments;
protected $calculatedSegments;
protected $password;
protected $ssoIdentities;
protected $passwordRecoveryToken;
protected $passwordRecoveryTokenDate;


/**
* @param array $values
* @return static
*/
public static function create(array $values = []): static
{
	$object = new static();
	$object->setValues($values);
	return $object;
}

/**
* Get active - Active
* @return bool|null
*/
public function getActive(): ?bool
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("active");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->active;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set active - Active
* @param bool|null $active
* @return $this
*/
public function setActive(?bool $active): static
{
	$this->active = $active;

	return $this;
}

/**
* Get gender - Gender
* @return string|null
*/
public function getGender(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("gender");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->gender;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set gender - Gender
* @param string|null $gender
* @return $this
*/
public function setGender(?string $gender): static
{
	$this->gender = $gender;

	return $this;
}

/**
* Get firstname - Firstname
* @return string|null
*/
public function getFirstname(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("firstname");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->firstname;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set firstname - Firstname
* @param string|null $firstname
* @return $this
*/
public function setFirstname(?string $firstname): static
{
	$this->firstname = $firstname;

	return $this;
}

/**
* Get lastname - Lastname
* @return string|null
*/
public function getLastname(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("lastname");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->lastname;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set lastname - Lastname
* @param string|null $lastname
* @return $this
*/
public function setLastname(?string $lastname): static
{
	$this->lastname = $lastname;

	return $this;
}

/**
* Get company - Company
* @return string|null
*/
public function getCompany(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("company");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->company;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set company - Company
* @param string|null $company
* @return $this
*/
public function setCompany(?string $company): static
{
	$this->company = $company;

	return $this;
}

/**
* Get email - Email
* @return string|null
*/
public function getEmail(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("email");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->email;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set email - Email
* @param string|null $email
* @return $this
*/
public function setEmail(?string $email): static
{
	$this->email = $email;

	return $this;
}

/**
* Get street - Street
* @return string|null
*/
public function getStreet(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("street");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->street;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set street - Street
* @param string|null $street
* @return $this
*/
public function setStreet(?string $street): static
{
	$this->street = $street;

	return $this;
}

/**
* Get zip - Zip
* @return string|null
*/
public function getZip(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("zip");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->zip;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set zip - Zip
* @param string|null $zip
* @return $this
*/
public function setZip(?string $zip): static
{
	$this->zip = $zip;

	return $this;
}

/**
* Get city - City
* @return string|null
*/
public function getCity(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("city");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->city;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set city - City
* @param string|null $city
* @return $this
*/
public function setCity(?string $city): static
{
	$this->city = $city;

	return $this;
}

/**
* Get countryCode - Country
* @return string|null
*/
public function getCountryCode(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("countryCode");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->countryCode;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set countryCode - Country
* @param string|null $countryCode
* @return $this
*/
public function setCountryCode(?string $countryCode): static
{
	$this->countryCode = $countryCode;

	return $this;
}

/**
* Get phone - phone
* @return string|null
*/
public function getPhone(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("phone");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->phone;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set phone - phone
* @param string|null $phone
* @return $this
*/
public function setPhone(?string $phone): static
{
	$this->phone = $phone;

	return $this;
}

/**
* Get idEncoded - Id Encoded
* @return string|null
*/
public function getIdEncoded(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("idEncoded");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->idEncoded;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set idEncoded - Id Encoded
* @param string|null $idEncoded
* @return $this
*/
public function setIdEncoded(?string $idEncoded): static
{
	$this->idEncoded = $idEncoded;

	return $this;
}

/**
* Get customerLanguage - Language
* @return string|null
*/
public function getCustomerLanguage(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("customerLanguage");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->customerLanguage;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set customerLanguage - Language
* @param string|null $customerLanguage
* @return $this
*/
public function setCustomerLanguage(?string $customerLanguage): static
{
	$this->customerLanguage = $customerLanguage;

	return $this;
}

/**
* Get newsletter - Newsletter
* @return \Pimcore\Model\DataObject\Data\Consent|null
*/
public function getNewsletter(): ?\Pimcore\Model\DataObject\Data\Consent
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("newsletter");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->newsletter;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set newsletter - Newsletter
* @param \Pimcore\Model\DataObject\Data\Consent|null $newsletter
* @return $this
*/
public function setNewsletter(?\Pimcore\Model\DataObject\Data\Consent $newsletter): static
{
	$this->newsletter = $newsletter;

	return $this;
}

/**
* Get newsletterConfirmed - Newsletter Confirmed
* @return bool|null
*/
public function getNewsletterConfirmed(): ?bool
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("newsletterConfirmed");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->newsletterConfirmed;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set newsletterConfirmed - Newsletter Confirmed
* @param bool|null $newsletterConfirmed
* @return $this
*/
public function setNewsletterConfirmed(?bool $newsletterConfirmed): static
{
	$this->newsletterConfirmed = $newsletterConfirmed;

	return $this;
}

/**
* Get newsletterConfirmToken - Newsletter Confirm Token
* @return string|null
*/
public function getNewsletterConfirmToken(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("newsletterConfirmToken");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->newsletterConfirmToken;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set newsletterConfirmToken - Newsletter Confirm Token
* @param string|null $newsletterConfirmToken
* @return $this
*/
public function setNewsletterConfirmToken(?string $newsletterConfirmToken): static
{
	$this->newsletterConfirmToken = $newsletterConfirmToken;

	return $this;
}

/**
* Get profiling - Profiling
* @return \Pimcore\Model\DataObject\Data\Consent|null
*/
public function getProfiling(): ?\Pimcore\Model\DataObject\Data\Consent
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("profiling");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->profiling;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set profiling - Profiling
* @param \Pimcore\Model\DataObject\Data\Consent|null $profiling
* @return $this
*/
public function setProfiling(?\Pimcore\Model\DataObject\Data\Consent $profiling): static
{
	$this->profiling = $profiling;

	return $this;
}

/**
* Get manualSegments - Manual Segments
* @return \Pimcore\Model\DataObject\Data\ObjectMetadata[]
*/
public function getManualSegments(): array
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("manualSegments");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->getClass()->getFieldDefinition("manualSegments")->preGetData($this);

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set manualSegments - Manual Segments
* @param \Pimcore\Model\DataObject\Data\ObjectMetadata[] $manualSegments
* @return $this
*/
public function setManualSegments(?array $manualSegments): static
{
	/** @var \Pimcore\Model\DataObject\ClassDefinition\Data\AdvancedManyToManyObjectRelation $fd */
	$fd = $this->getClass()->getFieldDefinition("manualSegments");
	$hideUnpublished = \Pimcore\Model\DataObject\Concrete::getHideUnpublished();
	\Pimcore\Model\DataObject\Concrete::setHideUnpublished(false);
	$currentData = $this->getManualSegments();
	\Pimcore\Model\DataObject\Concrete::setHideUnpublished($hideUnpublished);
	$isEqual = $fd->isEqual($currentData, $manualSegments);
	if (!$isEqual) {
		$this->markFieldDirty("manualSegments", true);
	}
	$this->manualSegments = $fd->preSetData($this, $manualSegments);
	return $this;
}

/**
* Get calculatedSegments - Calculated Segments
* @return \Pimcore\Model\DataObject\Data\ObjectMetadata[]
*/
public function getCalculatedSegments(): array
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("calculatedSegments");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->getClass()->getFieldDefinition("calculatedSegments")->preGetData($this);

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set calculatedSegments - Calculated Segments
* @param \Pimcore\Model\DataObject\Data\ObjectMetadata[] $calculatedSegments
* @return $this
*/
public function setCalculatedSegments(?array $calculatedSegments): static
{
	/** @var \Pimcore\Model\DataObject\ClassDefinition\Data\AdvancedManyToManyObjectRelation $fd */
	$fd = $this->getClass()->getFieldDefinition("calculatedSegments");
	$hideUnpublished = \Pimcore\Model\DataObject\Concrete::getHideUnpublished();
	\Pimcore\Model\DataObject\Concrete::setHideUnpublished(false);
	$currentData = $this->getCalculatedSegments();
	\Pimcore\Model\DataObject\Concrete::setHideUnpublished($hideUnpublished);
	$isEqual = $fd->isEqual($currentData, $calculatedSegments);
	if (!$isEqual) {
		$this->markFieldDirty("calculatedSegments", true);
	}
	$this->calculatedSegments = $fd->preSetData($this, $calculatedSegments);
	return $this;
}

/**
* Get password - Password
* @return string|null
*/
public function getPassword(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("password");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->password;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set password - Password
* @param string|null $password
* @return $this
*/
public function setPassword(?string $password): static
{
	$this->password = $password;

	return $this;
}

/**
* Get ssoIdentities - SSO Identities
* @return \Pimcore\Model\DataObject\SsoIdentity[]
*/
public function getSsoIdentities(): array
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("ssoIdentities");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->getClass()->getFieldDefinition("ssoIdentities")->preGetData($this);

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set ssoIdentities - SSO Identities
* @param \Pimcore\Model\DataObject\SsoIdentity[] $ssoIdentities
* @return $this
*/
public function setSsoIdentities(?array $ssoIdentities): static
{
	/** @var \Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation $fd */
	$fd = $this->getClass()->getFieldDefinition("ssoIdentities");
	$hideUnpublished = \Pimcore\Model\DataObject\Concrete::getHideUnpublished();
	\Pimcore\Model\DataObject\Concrete::setHideUnpublished(false);
	$currentData = $this->getSsoIdentities();
	\Pimcore\Model\DataObject\Concrete::setHideUnpublished($hideUnpublished);
	$isEqual = $fd->isEqual($currentData, $ssoIdentities);
	if (!$isEqual) {
		$this->markFieldDirty("ssoIdentities", true);
	}
	$this->ssoIdentities = $fd->preSetData($this, $ssoIdentities);
	return $this;
}

/**
* Get passwordRecoveryToken - Password Recovery Token
* @return string|null
*/
public function getPasswordRecoveryToken(): ?string
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("passwordRecoveryToken");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->passwordRecoveryToken;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set passwordRecoveryToken - Password Recovery Token
* @param string|null $passwordRecoveryToken
* @return $this
*/
public function setPasswordRecoveryToken(?string $passwordRecoveryToken): static
{
	$this->passwordRecoveryToken = $passwordRecoveryToken;

	return $this;
}

/**
* Get passwordRecoveryTokenDate - Password Recovery Token Date
* @return \Carbon\Carbon|null
*/
public function getPasswordRecoveryTokenDate(): ?\Carbon\Carbon
{
	if ($this instanceof PreGetValueHookInterface && !\Pimcore::inAdmin()) {
		$preValue = $this->preGetValue("passwordRecoveryTokenDate");
		if ($preValue !== null) {
			return $preValue;
		}
	}

	$data = $this->passwordRecoveryTokenDate;

	if ($data instanceof \Pimcore\Model\DataObject\Data\EncryptedField) {
		return $data->getPlain();
	}

	return $data;
}

/**
* Set passwordRecoveryTokenDate - Password Recovery Token Date
* @param \Carbon\Carbon|null $passwordRecoveryTokenDate
* @return $this
*/
public function setPasswordRecoveryTokenDate(?\Carbon\Carbon $passwordRecoveryTokenDate): static
{
	$this->passwordRecoveryTokenDate = $passwordRecoveryTokenDate;

	return $this;
}

}

