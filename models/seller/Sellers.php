<?php
declare(strict_types = 1);

namespace app\models\seller;

use app\controllers\SellersController;
use app\models\seller\active_record\SellersAR;
use app\models\traits\CreateAccessTrait;
use app\models\sys\users\Users;
use pozitronik\filestorage\traits\FileStorageTrait;

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
 *
 * @property string $fio ФИО
 * @property string $urlToEntity
 */
class Sellers extends SellersAR {
	use FileStorageTrait;
	use CreateAccessTrait;

	public $passportTranslation;
	public $migrationCard;
	public $placeOfStay;
	public $patent;
	public $residence;
	public $temporaryResidence;
	public $visa;
	public ?Users $sysUser = null;

	public const RUS_CLASS_NAME = 'Продавец';

	/**
	 * URL для нахождения продавца по ID
	 * @return string
	 */
	public function getUrlToEntity():string {
		return SellersController::to('index', ['SellersSearch[id]' => $this->id], true);
	}

}