<?php
declare(strict_types = 1);

namespace app\modules\notifications\migrations;
use app\modules\notifications\models\Notifications;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m201103_064856_sys_notifications
 */
class m201103_064856_sys_notifications extends Migration {
	private const TABLE_NAME = 'sys_notifications';
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'type' => $this->integer()->notNull()->defaultValue(Notifications::TYPE_DEFAULT)->comment('Тип уведомления'),
			'initiator' => $this->integer()->null()->comment('автор уведомления, null - система'),
			'receiver' => $this->integer()->null()->comment('получатель уведомления, null - определяется типом'),
			'object_id' => $this->integer()->null()->comment('идентификатор объекта уведомления, null - определяется типом'),
			'comment' => $this->text()->null(),
			'timestamp' => $this->dateTime()->defaultValue(new Expression('NOW()'))
		]);

		$this->createIndex(self::TABLE_NAME.'_type', self::TABLE_NAME, 'type');
		$this->createIndex(self::TABLE_NAME.'_initiator', self::TABLE_NAME, 'initiator');
		$this->createIndex(self::TABLE_NAME.'_receiver', self::TABLE_NAME, 'receiver');
		$this->createIndex(self::TABLE_NAME.'_object_id', self::TABLE_NAME, 'object_id');
		$this->createIndex(self::TABLE_NAME.'_type_receiver_object_id', self::TABLE_NAME, ['type', 'receiver', 'object_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable(self::TABLE_NAME);
	}

}
