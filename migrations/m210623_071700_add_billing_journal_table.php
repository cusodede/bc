<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210623_071700_add_billing_journal_table
*/
class m210623_071700_add_billing_journal_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('billing_journal', [
			'id' => $this->string(36)->notNull(),
			'rel_abonents_to_products_id' => $this->integer()->notNull()->comment('Связь с продуктом и абонентом'),
			'price' => $this->decimal(8, 2)->notNull()->comment('Величина списания'),
			'status_id' => $this->tinyInteger()->notNull()->comment('Статус списания'),
			'try_date' => $this->dateTime()->notNull()->comment('Дата списания/попытки списания'),
			'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
		]);

		$this->addPrimaryKey('pk_billing_journal', 'billing_journal', 'id');
		$this->createIndex('i_billing_journal_to_rel_abonents_to_products', 'billing_journal', 'rel_abonents_to_products_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('billing_journal');
	}
}
