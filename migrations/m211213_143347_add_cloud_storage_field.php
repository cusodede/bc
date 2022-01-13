<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m211213_143347_add_cloud_storage_field
 */
class m211213_143347_add_cloud_storage_field extends Migration {
	private const TABLE = 'sys_export';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn(self::TABLE, 'storage', $this->integer()->null()->comment('Облачное хранилище'));
		$this->createIndex('storage', self::TABLE, 'storage');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn(self::TABLE, 'storage');
	}

}
