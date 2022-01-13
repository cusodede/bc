<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m210716_123823_sys_remote_systems_tokens
 */
class m210716_123823_sys_remote_systems_tokens extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_users_remote_systems_tokens', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(),
			'remote_system_id' => $this->integer()->comment('id внешней системы, в которой авторизуемся, например.'),
			'token_type_id' => $this->integer()->comment('тип токена, access, refresh, bearer and etc.'),
			'token_value' => $this->string(),
			'expired_at' => $this->dateTime()->null(),
			'created_at' => $this->dateTime()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_users_remote_systems_tokens');
	}

}
