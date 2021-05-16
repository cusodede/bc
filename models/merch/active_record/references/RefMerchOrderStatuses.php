<?php
declare(strict_types = 1);

namespace app\models\merch\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * This is the model class for table "ref_merch_order_statuses".
 *
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property int $deleted
 */
class RefMerchOrderStatuses extends CustomisableReference {

	/*todo: возможно, тут лучше расширение для статусов*/
	public $menuCaption = "Статусы заказов";

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_merch_order_statuses';
	}

}
