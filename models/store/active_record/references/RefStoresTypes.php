<?php
declare(strict_types = 1);

namespace app\models\store\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefStoresTypes
 * Справочник типов магазинов
 */
class RefStoresTypes extends CustomisableReference {

	public $menuCaption = "Типы магазинов";
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_stores_types';
	}
}