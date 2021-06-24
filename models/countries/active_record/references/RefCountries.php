<?php
declare(strict_types = 1);

namespace app\models\countries\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefCountries
 *
 * @property int is_homeland Это Россия?
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

	/**
	 * @return RefCountries|null
	 */
	public static function getHomelandCountry():?RefCountries {
		return self::findOne(['is_homeland' => true]);
	}
}