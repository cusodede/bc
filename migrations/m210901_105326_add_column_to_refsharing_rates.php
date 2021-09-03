<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\revshare_rates\RevShareRates;

/**
* Class m210901_105326_add_column_to_refsharing_rates
*/
class m210901_105326_add_column_to_refsharing_rates extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn(RevShareRates::tableName(), 'product_id', $this->integer()->notNull()->after('id')->comment('id продукта'));
		$this->addForeignKey('fk_ref_share_product_id', RevShareRates::tableName(), 'product_id', 'products', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropForeignKey('fk_ref_share_product_id', RevShareRates::tableName());
		$this->dropColumn(RevShareRates::tableName(), 'product_id');
	}
}
