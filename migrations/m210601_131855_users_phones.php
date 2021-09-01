<?php
declare(strict_types = 1);
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m210601_131855_users_phones
 */
class m210601_131855_users_phones extends Migration {
	private const TABLE_NAME = 'phones';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'phone' => $this->string()->notNull()->comment('Телефон'),
			'create_date' => $this->dateTime()->defaultValue(new Expression('NOW()'))->comment('Дата регистрации'),
			'status' => $this->integer()->null()->comment('Статус'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createIndex(self::TABLE_NAME.'_phone', self::TABLE_NAME, 'phone');
		$this->createIndex(self::TABLE_NAME.'_status', self::TABLE_NAME, 'status');
		$this->createIndex(self::TABLE_NAME.'_deleted', self::TABLE_NAME, 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable(self::TABLE_NAME);
	}

}
