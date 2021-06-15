<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210615_072629_create_table_rel_abonents_to_products
*/
class m210615_072629_create_table_rel_abonents_to_products extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('relation_abonents_to_products', [
			'id' => $this->primaryKey(),
			'abonent_id' => $this->integer()->notNull(),
			'product_id' => $this->integer()->notNull(),
			'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
		]);

		$this->createIndex('ui_ratp_$anonent_id$product_id', 'relation_abonents_to_products', ['abonent_id', 'product_id'], true);
		$this->addForeignKey('fk_ratp_to_abonents', 'relation_abonents_to_products', 'abonent_id', 'abonents', 'id');
		$this->addForeignKey('fk_ratp_to_products', 'relation_abonents_to_products', 'product_id', 'products', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('relation_abonents_to_products');
	}
}
