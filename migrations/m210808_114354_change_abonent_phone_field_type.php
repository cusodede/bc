<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210808_114354_change_abonent_phone_field_type
*/
class m210808_114354_change_abonent_phone_field_type extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('abonents', 'phone', $this->string()->notNull());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('abonents', 'phone', $this->char(11)->notNull());
	}

}
