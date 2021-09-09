<?php
declare(strict_types = 1);

use app\components\db\Migration;

/**
* Class m210901_105326_add_column_to_refsharing_rates
*/
class m210901_105326_add_column_to_refsharing_rates extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('refsharing_rates', 'product_id', $this->integer()->notNull()->after('id')->comment('id продукта'));
		$this->addForeignKey('fk_ref_share_product_id', 'refsharing_rates', 'product_id', 'products', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropForeignKey('fk_ref_share_product_id', 'refsharing_rates');
		$this->dropColumn('refsharing_rates', 'product_id');
	}
}
