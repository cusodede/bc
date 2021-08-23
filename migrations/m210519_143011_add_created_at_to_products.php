<?php
declare(strict_types = 1);

use app\models\products\Products;

/**
* Class m210519_143011_add_created_at_to_products
*/
class m210519_143011_add_created_at_to_products extends \app\components\db\Migration
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
				$this->addColumn(Products::tableName(), 'updated_at', $this->timestamp()->notNull()->comment('Дата обновления продукта'));
				if (!$this->createOnUpdateTrigger(Products::tableName())) {
					throw new \yii\db\Exception('Не удалось создать триггер для таблицы ' . Products::tableName());
				}
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
