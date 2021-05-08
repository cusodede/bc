<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * This is the model class for table "ref_merch_order_states".
 *
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property int $deleted
 */
class RefMerchOrderStates extends CustomisableReference {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_merch_order_states';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['deleted'], 'integer'],
			[['name', 'color'], 'string', 'max' => 255],
		];
	}

}
