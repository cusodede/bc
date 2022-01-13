<?php
declare(strict_types = 1);

namespace app\modules\recaptcha\forms;

use yii\base\Model;

/**
 * Class RecaptchaForm
 * @package app\modules\recaptcha\forms
 */
class RecaptchaForm extends Model {
	public ?string $token = null;
	public ?string $os = null;

	private const OS_TYPES = ['ios', 'android', 'web'];

	/**
	 * @return array
	 */
	public function rules():array {
		return [
			[['token', 'os'], 'required'],
			[['token', 'os'], 'string'],
			['os', 'in', 'range' => self::OS_TYPES]
		];
	}

	/**
	 * @return array
	 */
	public function attributeLabels():array {
		return [
			'token' => 'Токен reCaptcha',
			'os' => 'Операционная система'
		];
	}
}
