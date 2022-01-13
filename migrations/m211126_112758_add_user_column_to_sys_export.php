<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m211126_112758_add_user_column_to_sys_export
 */
class m211126_112758_add_user_column_to_sys_export extends Migration {
	private const TABLE = 'sys_export';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn(self::TABLE, 'user', $this->integer()->null()->comment('Пользователь'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn(self::TABLE, 'user');
	}

}
