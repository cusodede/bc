<?php
declare(strict_types = 1);

use app\components\db\Migration;

/**
 * Class m210412_114709_sys_users_update_pwd_flag
 */
class m210412_114709_sys_users_update_pwd_flag extends Migration {
	private const TABLE_NAME = 'sys_users';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn(self::TABLE_NAME, 'is_pwd_outdated', $this->boolean()->notNull()->defaultValue(false)->after('salt')->comment('Ожидается смена пароля'));
		$this->createIndex(self::TABLE_NAME.'_is_pwd_outdated', self::TABLE_NAME, 'is_pwd_outdated');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex(self::TABLE_NAME.'_is_pwd_outdated', self::TABLE_NAME);
		$this->dropColumn(self::TABLE_NAME, 'is_pwd_outdated');
	}

}
