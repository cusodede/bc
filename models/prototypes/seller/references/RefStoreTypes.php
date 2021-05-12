<?php
declare(strict_types = 1);

namespace app\models\prototypes\seller\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefStoreTypes
 * Справочник типов магазинов
 */
class RefStoreTypes extends CustomisableReference {

	public $menuCaption = "Типы магазинов";
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_store_types';
	}
}