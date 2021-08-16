<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210812_112259_remove_ticket_journal_table
*/
class m210812_112259_remove_ticket_journal_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropTable('ticket_journal');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
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
	}

}
