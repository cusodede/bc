<?php
declare(strict_types = 1);

use app\components\db\Migration;

/**
 * Handles the creation of table notification_template.
 */
class m210906_073043_create_notification_templates_table extends Migration
{
	private const TABLE_NAME = 'notification_templates';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'type' => $this->smallInteger()->notNull()->comment('Типа (mail, sms)'),
			'message_body' => $this->text()->notNull()->comment('Тело сообщения'),
			'subject' => $this->string(255)->notNull()->comment('Тема письма'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
			'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
		]);
		$this->createIndex('idx-type', self::TABLE_NAME, 'type');
		$this->createIndex('idx-deleted', self::TABLE_NAME, 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex('idx-type', self::TABLE_NAME);
		$this->dropIndex('idx-deleted', self::TABLE_NAME);
		$this->dropTable(self::TABLE_NAME);
	}
}
