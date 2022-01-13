<?php
declare(strict_types = 1);

namespace app\models\site\auth;

use app\models\phones\PhoneNumberValidator;
use yii\base\Model;

/**
 * Class ConfirmLoginForm
 * @package app\models\site\auth
 */
class ConfirmLoginForm extends Model {
	public ?string $phone_number = null;
	public ?string $sms_code = null;

	/**
	 * @return array
	 */
	public function rules():array {
		return [
			[['phone_number', 'sms_code'], 'required'],
			['phone_number', PhoneNumberValidator::class],
		];
	}

	/**
	 * @return array
	 */
	public function attributeLabels():array {
		return [
			'phone_number' => 'Номер телефона',
			'sms_code' => 'Код из смс'
		];
	}
}
