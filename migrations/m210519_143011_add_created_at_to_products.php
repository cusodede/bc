<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\products\Products;

/**
* Class m210519_143011_add_created_at_to_products
*/
class m210519_143011_add_created_at_to_products extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(Products::tableName(), 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления продукта'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(Products::tableName(), 'updated_at');
	}

}
