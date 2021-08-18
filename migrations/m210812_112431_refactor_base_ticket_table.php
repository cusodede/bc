<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210812_112431_refactor_base_ticket_table
*/
class m210812_112431_refactor_base_ticket_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ticket', 'stage_id', $this->integer()->notNull()->after('type'));
		$this->addColumn('ticket', 'status', $this->smallInteger()->notNull()->after('stage_id'));
		$this->addColumn('ticket', 'journal_data', $this->json()->after('status'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ticket', 'stage_id');
		$this->dropColumn('ticket', 'status');
		$this->dropColumn('ticket', 'journal_data');
	}

}
