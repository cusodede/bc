<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210726_143247_create_table_tor_ticket_journal
*/
class m210726_143247_create_table_tor_ticket_journal extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ticket', [
			'id' => $this->char(36)->notNull(),
			'type' => $this->tinyInteger()->notNull(),
			'created_by' => $this->integer(),
			'created_at' => $this->dateTime()->notNull(),
			'completed_at' => $this->dateTime()
		]);
		$this->addPrimaryKey('pk_ticket_$id', 'ticket', 'id');

		$this->createTable('ticket_journal', [
			'id' => $this->char(36)->notNull(),
			'ticket_id' => $this->char(36)->notNull(),
			'operation_code' => $this->integer()->notNull(),
			'status' => $this->smallInteger()->notNull(),
			'user_data' => $this->json()->comment('Специфические данные для конкретного статуса'),
			'created_at' => $this->dateTime()->notNull()
		]);
		$this->addPrimaryKey('pk_ticket_journal_$id', 'ticket_journal', 'id');
		$this->addForeignKey('fk_ticket_journal_to_ticket', 'ticket_journal', 'ticket_id', 'ticket', 'id');

		$this->createTable('ticket_product_subscription', [
			'id' => $this->char(36)->notNull(),
			'action' => $this->tinyInteger()->notNull(),
			'rel_abonents_to_products_id' => $this->integer()
		]);
		$this->addPrimaryKey('pk_ticket_product_subscription_$id', 'ticket_product_subscription', 'id');
		$this->addForeignKey('fk_ticket_product_subscription_to_ticket', 'ticket_product_subscription', 'id', 'ticket', 'id');
		$this->addForeignKey(
			'fk_ticket_product_subscription_to_abonents_products',
			'ticket_product_subscription',
			'rel_abonents_to_products_id',
			'relation_abonents_to_products',
			'id'
		);

		$this->createTable('relation_ticket_to_billing', [
			'id' => $this->primaryKey(),
			'ticket_id' => $this->char(36)->notNull(),
			'billing_id' => $this->char(36)->notNull()
		]);

		$this->addForeignKey('fk_relation_ticket_to_billing_to_ticket', 'relation_ticket_to_billing', 'ticket_id', 'ticket', 'id');
		$this->addForeignKey('fk_relation_ticket_to_billing_to_billing_journal', 'relation_ticket_to_billing', 'billing_id', 'billing_journal', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('relation_ticket_to_billing');
		$this->dropTable('ticket_product_subscription');
		$this->dropTable('ticket_journal');
		$this->dropTable('ticket');
	}
}
