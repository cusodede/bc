<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210615_073331_create_table_for_product_statuses
*/
class m210615_073331_create_table_for_product_statuses extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('product_statuses', [
			'id' => $this->primaryKey(),
			'rel_abonents_to_products_id' => $this->integer()->notNull(),
			'status_id' => $this->integer()->notNull(),
			'start_date' => $this->dateTime()->notNull()->comment('Дата начала предоставления услуги'),
			'end_date' => $this->dateTime()->comment('Дата окончания предоставления услуги'),
			'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
		]);

		$this->addForeignKey('fk_ps_to_rel_abonents_to_products', 'product_statuses', 'rel_abonents_to_products_id', 'relation_abonents_to_products', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('product_statuses');
	}
}
