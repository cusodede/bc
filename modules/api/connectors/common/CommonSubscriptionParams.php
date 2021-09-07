<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\common;

use app\common\Arrayable;
use app\models\abonents\Abonents;
use app\models\phones\Phones;
use InvalidArgumentException;
use yii\base\BaseObject;

/**
 * Источник данных для формирования подписки.
 * Для удобства накручивания валидации при инициализации атрибутов, а также для формирования body-параметров для API.
 *
 * Class CommonSubscriptionParams
 * @package app\modules\api\connectors\common
 *
 * @property string $phone
 * @property string $email
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $expireDate
 */
class CommonSubscriptionParams extends BaseObject implements Arrayable
{
	/**
	 * @var string
	 */
	private string $_phone = '';
	/**
	 * @var string
	 */
	private string $_email = '';
	/**
	 * @var string
	 */
	private string $_firstName = '';
	/**
	 * @var string
	 */
	private string $_middleName = '';
	/**
	 * @var string
	 */
	private string $_lastName = '';
	/**
	 * @var string
	 */
	private string $_expireDate = '';

	/**
	 * @param string $phone
	 */
	public function setPhone(string $phone): void
	{
		if (!Phones::isValidNumber($phone) || (null === $formattedPhone = Phones::nationalFormat($phone))) {
			throw new InvalidArgumentException('Некорректное значение телефонного номера.');
		}

		$this->_phone = '7' . $formattedPhone;
	}

	/**
	 * @return string
	 */
	public function getPhone(): string
	{
		return $this->_phone;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email): void
	{
		$this->_email = $email;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->_email;
	}

	/**
	 * @param string $firstName
	 */
	public function setFirstName(string $firstName): void
	{
		$this->_firstName = $firstName;
	}

	/**
	 * @return string
	 */
	public function getFirstName(): string
	{
		return $this->_firstName;
	}

	/**
	 * @param string $middleName
	 */
	public function setMiddleName(string $middleName): void
	{
		$this->_middleName = $middleName;
	}

	/**
	 * @return string
	 */
	public function getMiddleName(): string
	{
		return $this->_middleName;
	}

	/**
	 * @param string $lastName
	 */
	public function setLastName(string $lastName): void
	{
		$this->_lastName = $lastName;
	}

	/**
	 * @return string
	 */
	public function getLastName(): string
	{
		return $this->_lastName;
	}

	/**
	 * @param string $date
	 */
	public function setExpireDate(string $date): void
	{
		$this->_expireDate = date_create($date)->format('d.m.Y');
	}

	/**
	 * @return string
	 */
	public function getExpireDate(): string
	{
		return $this->_expireDate;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray(): array
	{
		return [
			'phone'           => $this->_phone,
			'email'           => $this->_email,
			'first_name'      => $this->_firstName,
			'last_name'       => $this->_lastName,
			'middle_name'     => $this->_middleName,
			'subscription_to' => $this->_expireDate
		];
	}

	/**
	 * @param Abonents $abonent
	 * @return static
	 */
	public static function createInstance(Abonents $abonent): self
	{
		return new static([
			'phone'      => $abonent->phone,
			'lastName'   => $abonent->surname,
			'middleName' => $abonent->patronymic,
			'firstName'  => $abonent->name
		]);
	}
}