<?php
declare(strict_types = 1);

namespace app\models\site;

use app\controllers\SiteController;
use app\models\sys\users\Users;
use Yii;
use yii\base\Model;

/**
 * Восстановление пароля на email
 * (контрольные вопросы и прочую чушь не делаю без необходимости)
 * Для защиты от перебора почт (хотя кому это нужно), результата у операции нет.
 *
 * @property null|string $email Адрес, на который гость пробует восстановить пароль
 * @todo: капча
 */
class RestorePasswordForm extends Model {
	public $email;

	/**
	 * @inheritDoc
	 */
	public function rules():array {
		return [
			[['email'], 'required'],
			[['email'], 'email']
		];
	}

	/**
	 * @inheritDoc
	 */
	public function attributeLabels():array {
		return [
			'email' => 'Почтовый адрес, указанный при регистрации'
		];
	}

	/**
	 * Восстанавливает пароль пользователя по емайлу
	 * @return void
	 */
	public function doSendCode():void {
		if ($this->validate()) {
			if (null === $user = Users::findByEmail($this->email)) return;
			$restoreCode = Users::generateSalt();
			$user->restore_code = $restoreCode;
			if (!$user->save()) return;
			Yii::$app->mailer->compose('site/restore-password', [
				'user' => $user,
				'restoreUrl' => SiteController::to('reset-password', ['code' => $user->restore_code], true)
			])
				->setFrom('todo@config.param')/*todo*/
				->setTo($user->email)
				->setSubject('Запрос восстановления пароля')
				->send();
		}
	}

}
