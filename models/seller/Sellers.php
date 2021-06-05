<?php
declare(strict_types = 1);

namespace app\models\seller;

use app\controllers\SellersController;
use app\models\seller\active_record\SellersAR;
use app\models\site\RestorePasswordForm;
use app\models\sys\users\Users;
use pozitronik\filestorage\traits\FileStorageTrait;
use Yii;

/**
 * Class Sellers
 * Конкретный продавец
 * @property mixed $passportTranslation Скан нотариально заверенного перевода (в случае если нет транскрипции на
 * кириллицу)
 * @property mixed $migrationCard Миграционная карта (всем, кроме граждан Беларуси)
 * @property mixed $placeOfStay Отрывная часть бланка к уведомлению о прибытии в место пребывания
 * @property mixed $patent Патент + квитанция об оплате
 * @property mixed $residence Вид на жительство
 * @property mixed $temporaryResidence Разрешение на временное проживание
 * @property mixed $visa Виза
 * @property array $registrationErrors массив с ошибками во время регистрации
 *
 * @property string $fio ФИО
 */
class Sellers extends SellersAR {
	use FileStorageTrait;

	public $passportTranslation;
	public $migrationCard;
	public $placeOfStay;
	public $patent;
	public $residence;
	public $temporaryResidence;
	public $visa;
	public ?Users $sysUser = null;
	public array $registrationErrors = [];

	/**
	 * @return string
	 */
	public function getFio():string {
		return trim("{$this->surname} {$this->name} {$this->patronymic}");
	}

	/**
	 * Создает учетную запись для продавца и привязывает ее к продавцу. Если не удается, то отправляется
	 * письмо с ошибками администратору.
	 * @return void
	 */
	public function createAccess():void {
		if (!$this->createUser()) {
			$this->registrationErrors[] = 'Не удалось создать системного пользователя';
		}

		if ($this->registrationErrors) {
			$this->sendErrors();
		} else {
			$this->confirmRegistrationRequest();
		}
	}

	/**
	 * Создает учетную запись пользователя на основе созданного продавца
	 * @return bool
	 */
	public function createUser():bool {
		$user = new Users([
			'login' => $this->login,
			'username' => $this->fio,
			'password' => Users::DEFAULT_PASSWORD,
			'comment' => "User automatically created from seller's registration",
			'email' => $this->email
		]);
		if (!$user->save()) return false;
		$this->relatedUser = $user;
		return true;
	}

	/**
	 * Пользователь создается с паролем по умолчанию. Для входа в систему нужно поменять этот пароль. Мы отправляем
	 * письмо с ссылкой для изменения пароля
	 * @return void
	 */
	public function confirmRegistrationRequest():void {
		if ($this->saveRestoreCode()) {
			RestorePasswordForm::sendRestoreMail(
				'site/confirm-registration',
				$this->relatedUser,
				'Подтверждение регистрации на '.Yii::$app->name
			);
		}
	}

	/**
	 * @return bool
	 */
	public function saveRestoreCode():bool {
		$restoreCode = Users::generateSalt();
		$this->relatedUser->restore_code = $restoreCode;
		return $this->relatedUser->save();
	}

	/**
	 * Отправка ошибок регистрации администратору
	 * @return void
	 */
	public function sendErrors():void {
		Yii::$app->mailer->compose('sellers/registration-errors', [
			'seller' => $this,
			'sellerUrl' => SellersController::to(
				'index',
				['SellersSearch[id]' => $this->id],
				true
			),
			'errors' => $this->registrationErrors
		])
			->setFrom('todo@config.param')/*todo*/
			->setTo('todo@config.param')/*todo*/
			->setSubject("Ошибки при регистрации продавца {$this->fio}")
			->send();
	}

}