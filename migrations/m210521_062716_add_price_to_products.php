<?php
declare(strict_types = 1);
use yii\db\Migration;

use app\models\products\Products;

/**
* Class m210521_062716_add_price_to_products
*/
class m210521_062716_add_price_to_products extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(Products::tableName(), 'price', $this->decimal(8,2)->after('name')->notNull()->defaultValue(0));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(Products::tableName(), 'price');
	}

}
