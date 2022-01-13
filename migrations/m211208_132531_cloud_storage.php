<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m211208_132531_cloud_storage
 */
class m211208_132531_cloud_storage extends Migration {
	private const TABLE = 'sys_cloud_storage';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(
			self::TABLE,
			[
				'id' => $this->primaryKey(),
				'bucket' => $this->string()->notNull()->comment('Ключ файла в облаке'),
				'key' => $this->string()->notNull()->comment('Корзина в облаке'),
				'filename' => $this->string()->notNull()->comment('Название файла')
			]
		);
		$this->createIndex('bucket', self::TABLE, 'bucket');
		$this->createIndex('key', self::TABLE, 'key');
		$this->createIndex('filename', self::TABLE, 'filename');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable(self::TABLE);
	}

}
