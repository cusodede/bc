<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210812_134943_rename_ticket_subscription_table
*/
class m210812_134943_rename_ticket_subscription_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameTable('ticket_product_subscription', 'ticket_subscription');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameTable('ticket_subscription', 'ticket_product_subscription');
	}

}
