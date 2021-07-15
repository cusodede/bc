<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210712_125529_add_full_desc_field_to_products_table
*/
class m210712_125529_add_full_desc_field_to_products_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('products', 'description', $this->string(255)->notNull()->comment('Описание продукта'));
		$this->addColumn('products', 'ext_description', $this->text()->notNull()->comment('Полное описание продукта')->after('description'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('products', 'ext_description');
		$this->alterColumn('products', 'description', $this->string(255)->comment('Описание продукта'));
	}

}
