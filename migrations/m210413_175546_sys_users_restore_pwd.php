<?php
declare(strict_types = 1);

use app\components\db\Migration;

/**
 * Class m210413_175546_sys_users_restore_pwd
 */
class m210413_175546_sys_users_restore_pwd extends Migration {
	private const TABLE_NAME = 'sys_users';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn(self::TABLE_NAME, 'restore_code', $this->string('40')->null()->after('salt')->comment('Код восстановления'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn(self::TABLE_NAME, 'restore_code');
	}

}
