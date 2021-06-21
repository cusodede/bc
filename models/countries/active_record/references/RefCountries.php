<?php
declare(strict_types = 1);

namespace app\models\countries\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefCountries
 */
class RefCountries extends CustomisableReference {

	public string $menuCaption = "Справочник стран";

	public $moduleId = "Страны";
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_countries';
	}
}