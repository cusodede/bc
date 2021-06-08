<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\products\Products;

/**
* Class m210608_132238_drop_product_types
*/
class m210608_132238_drop_product_types extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropForeignKey('fk-products-type_id', Products::tableName());
		$this->dropTable('ref_products_types');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->createTable('ref_products_types', [
			'id' => $this->primaryKey(),
			'name' => $this->string(64)->notNull()->comment('Название типа ародукта'),
			'deleted' => $this->boolean()->notNull()->defaultValue(0)->comment('Флаг активности'),
		]);
	}

}
