<?php
declare(strict_types = 1);
namespace app\modules\notifications\migrations;

use yii\db\Migration;

/**
 * Class m000000_000000_init_notifications_module
 */
class m000000_000000_init_notifications_module extends Migration {
	private const TABLE_NAME = 'sys_notifications';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'user' => $this->integer()->defaultValue(null),
			'type' => $this->string(255)->null()->comment('Notification handler'),
			'data' => $this->binary()->comment('Notification data'),
			'create_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
			'sent_date' => $this->timestamp()->null()->defaultValue(null),
			'delegate' => $this->string(255)->null(),
		]);

		$this->createIndex('user', self::TABLE_NAME, 'user');
		$this->createIndex('type', self::TABLE_NAME, 'type');
		$this->createIndex('delegate', self::TABLE_NAME, 'delegate');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable(self::TABLE_NAME);

	}
}
