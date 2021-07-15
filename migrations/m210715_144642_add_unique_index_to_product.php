<?php
declare(strict_types = 1);
use yii\db\Migration;
use app\models\products\Products;

/**
* Class m210715_144642_add_unique_index_to_product
*/
class m210715_144642_add_unique_index_to_product extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createIndex('idx-name-partner_id-type_id', Products::tableName(), ['name', 'partner_id', 'type_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex('idx-name-partner_id-type_id', Products::tableName());
	}

}
