<?php
declare(strict_types = 1);

use app\components\db\Migration;
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
		switch ($this->db->driverName) {
			case 'mysql':
				$this->addColumn(Products::tableName(), 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления продукта'));
			break;
			case 'pgsql':
				$this->addColumn(Products::tableName(), 'updated_at', $this->timestamp()->comment('Дата обновления продукта'));
				$this->createOnUpdateTrigger(Products::tableName());
			break;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(Products::tableName(), 'updated_at');
	}

}
