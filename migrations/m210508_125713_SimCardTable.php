<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
 * Class m210508_125713_SimCardTable
 */
class m210508_125713_SimCardTable extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('m_simcard', [
			'id' => $this->primaryKey(),
			'ICCID' => $this->integer(20)->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue(0)
		]);

		$this->createIndex('active', 'm_simcard', 'active');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('m_simcard');
	}

}
