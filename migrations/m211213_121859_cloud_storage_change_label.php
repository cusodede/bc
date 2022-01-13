<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m211213_121859_cloud_storage_change_label
 */
class m211213_121859_cloud_storage_change_label extends Migration {
	private const TABLE = 'sys_cloud_storage';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn(
			self::TABLE,
			'bucket',
			$this->string()->notNull()->comment('Корзина в облаке')
		);
		$this->alterColumn(
			self::TABLE,
			'key',
			$this->string()->notNull()->comment('Ключ файла в облаке')
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn(
			self::TABLE,
			'key',
			$this->string()->notNull()->comment('Корзина в облаке')
		);
		$this->alterColumn(
			self::TABLE,
			'bucket',
			$this->string()->notNull()->comment('Ключ файла в облаке')
		);
	}

}
