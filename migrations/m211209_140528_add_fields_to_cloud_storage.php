<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m211209_140528_add_fields_to_cloud_storage
 */
class m211209_140528_add_fields_to_cloud_storage extends Migration {
	private const TABLE = 'sys_cloud_storage';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn(
			self::TABLE,
			'uploaded',
			$this->boolean()->notNull()->defaultValue(false)->comment('Удалено')
		);
		$this->addColumn(
			self::TABLE,
			'deleted',
			$this->boolean()->notNull()->defaultValue(false)->comment('Загружено')
		);
		$this->addColumn(
			self::TABLE,
			'created_at',
			$this->dateTime()->comment('Дата создания')
		);

		$this->createIndex('created_at', self::TABLE, 'created_at');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn(self::TABLE, 'deleted');
		$this->dropColumn(self::TABLE, 'uploaded');
		$this->dropColumn(self::TABLE, 'created_at');
	}

}
