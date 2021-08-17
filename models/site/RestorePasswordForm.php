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
class RestorePasswordForm extends Model
{
	public ?string $email = null;

	public const RESTORE_CODE_LIFETIME = 1200;

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
	public function sendCode(): void
	{
		if ($this->validate()) {
			$user = Users::findByEmail($this->email);
			if ($user) {
				$user->restore_code = $this->generateRestoreCode();
				if (!$user->save()) {
					return;
				}

				static::sendRestoreMail($user);
			}
		}
	}

	/**
	 * @return string
	 */
	private function generateRestoreCode(): string
	{
		return Users::generateSalt() . '_t' . (time() + self::RESTORE_CODE_LIFETIME);
	}

	/**
	 * @param string $restoreCode
	 * @return string
	 */
	public static function getRestoreUrl(string $restoreCode): string
	{
		return SiteController::to('reset-password', ['code' => $restoreCode], true);
	}

	/**
	 * @param Users $user
	 * @param string $view
	 * @return void
	 */
	public static function sendRestoreMail(Users $user, string $view = 'site/restore-password'): void
	{
		Yii::$app->mailer
			->compose($view, ['user' => $user, 'restoreUrl' => static::getRestoreUrl($user->restore_code)])
			->setFrom('todo@config.param')/*todo*/
			->setTo($user->email)
			->setSubject('Запрос восстановления пароля')
			->send();
	}
}
