<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210728_095107_delete_try_date_field_from_billing_journal
*/
class m210728_095107_delete_try_date_field_from_billing_journal extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('billing_journal', 'try_date');
		$this->alterColumn('billing_journal', 'created_at', $this->dateTime()->notNull());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('billing_journal', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
		$this->addColumn('billing_journal', 'try_date', $this->dateTime());
	}

}
