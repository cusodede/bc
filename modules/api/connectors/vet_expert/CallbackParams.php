<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\vet_expert;

use app\models\phones\Phones;
use DateTime;
use InvalidArgumentException;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use pozitronik\helpers\ArrayHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Источник данных для формирования подписки в VetExpert.
 * Для удобства накручивания валидации при инициализации атрибутов, а также для формирования body-параметров для API.
 *
 * Class CallbackParams
 * @package app\modules\api\connectors\vetexpert
 */
class CallbackParams
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
	 * @var string|null
	 */
	private ?string $_middleName = null;
	/**
	 * @var string
	 */
	private string $_lastName = '';
	/**
	 * @var string
	 */
	private string $_subscriptionTo = '';
	/**
	 * @var Signer компонент для подписи body-параметров
	 */
	private ?Signer $_signer;
	/**
	 * @var Key плюч для подписи
	 */
	private ?Key $_signerKey;

	/**
	 * CallbackParams constructor.
	 * @throws InvalidConfigException
	 */
	public function __construct()
	{
		$this->_signer    = Instance::ensure(ArrayHelper::getValue(Yii::$app->params, 'callback.signer', new InvalidConfigException("Callback signer not set")), Signer::class);
		$this->_signerKey = Instance::ensure(ArrayHelper::getValue(Yii::$app->params, 'callback.signerKey', new InvalidConfigException("Callback signerKey not set")), Key::class);
	}

	/**
	 * @param string $phone
	 */
	public function setPhone(string $phone): void
	{
		if (!Phones::isValidNumber($phone)) {
			throw new InvalidArgumentException('Некорректное значение телефонного номера.');
		}

		/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
		$this->_phone = str_replace('+', '', Phones::defaultFormat($this->_phone));
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
	 * @param string|null $middleName
	 */
	public function setMiddleName(?string $middleName = null): void
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
	 * @param DateTime $date
	 */
	public function setSubscriptionTo(DateTime $date): void
	{
		$this->_subscriptionTo = $date->format('d.m.Y');
	}

	/**
	 * @return string
	 */
	public function getSubscriptionTo(): string
	{
		return $this->_subscriptionTo;
	}

	/**
	 * @return array список параметров для запроса на подписку/обновление для VetExpert.
	 */
	public function getParams(): array
	{
		$params         = [
			'phone' => $this->_phone,
			'email' => $this->_email,
			'first_name' => $this->_firstName,
			'last_name' => $this->_lastName,
			'middle_name' => $this->_middleName,
			'subscription_to' => $this->_subscriptionTo
		];
		$params['sign'] = $this->getParamsSignature($params);

		return $params;
	}

	/**
	 * @param array $params
	 * @return string подпись всех параметров запроса.
	 */
	private function getParamsSignature(array $params): string
	{
		ksort($params);
		array_walk($params, static function(&$item, $key) {
			$item = "{$key}={$item}";
		});
		return $this->_signer->sign(implode('&', $params), $this->_signerKey);
	}
}