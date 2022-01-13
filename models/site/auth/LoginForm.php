<?php
declare(strict_types = 1);

namespace app\models\site\auth;

use app\models\phones\PhoneNumberValidator;
use app\models\site\validators\ActiveUserWithPhoneValidator;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\models\site\auth
 */
class LoginForm extends Model {
	public ?string $phone_number = null;

	/**
	 * @return array
	 */
	public function rules():array {
		return [
			['phone_number', 'required'],
			['phone_number', PhoneNumberValidator::class],
			['phone_number', ActiveUserWithPhoneValidator::class]
		];
	}

	/**
	 * @return array
	 */
	public function attributeLabels():array {
		return [
			'phone_number' => 'Номер телефона'
		];
	}
}
