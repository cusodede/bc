<?php
declare(strict_types = 1);

namespace app\models\store\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Справочник каналов продаж
 */
class RefSellingChannels extends CustomisableReference {

	public $menuCaption = "Каналы продаж";
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_selling_channels';
	}
}