<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m211122_150719_add_sys_export
 */
class m211122_150719_add_sys_export extends Migration {
	private const TABLE = 'sys_export';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(
			self::TABLE,
			[
				'id' => $this->primaryKey(),
				'created_at' => $this->dateTime()->notNull()->comment('Дата создания'),
				'updated_at' => $this->dateTime()->notNull()->comment('Дата изменения'),
				'status' => $this->smallInteger()->null()->comment('Статус выгрузки'),
				'extra_data' => $this->text()->null()->comment('Доп. информация'),
				'deleted' => $this->boolean()->notNull()->defaultValue(false)
			]
		);

		$this->createIndex('status', self::TABLE, 'status');
		$this->createIndex('created_at', self::TABLE, 'created_at');
		$this->createIndex('updated_at', self::TABLE, 'updated_at');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable(self::TABLE);
	}

}
