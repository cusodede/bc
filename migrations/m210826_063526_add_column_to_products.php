<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\products\Products;

/**
 * Class m210826_063526_add_column_to_products
 */
class m210826_063526_add_column_to_products extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(Products::tableName(), 'refsharing_rates_id', $this->integer()->notNull()->after('partner_id')->comment('id ставки рефшеринга'));
		$this->addForeignKey('fk_products_refsharing_rates_id', 'products', 'refsharing_rates_id', 'refsharing_rates', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk_products_refsharing_rates_id', 'products');
		$this->dropColumn(Products::tableName(), 'refsharing_rates_id');
	}

}
