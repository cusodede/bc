<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m211220_134207_rename_labels
 */
class m211220_134207_rename_labels extends Migration {
	private const TABLE = 'sys_cloud_storage';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn(
			self::TABLE,
			'deleted',
			$this->boolean()->notNull()->defaultValue(false)->comment('Удалено')
		);
		$this->alterColumn(
			self::TABLE,
			'uploaded',
			$this->boolean()->notNull()->defaultValue(false)->comment('Загружено')
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn(
			self::TABLE,
			'deleted',
			$this->boolean()->notNull()->defaultValue(false)->comment('Загружено')
		);
		$this->alterColumn(
			self::TABLE,
			'uploaded',
			$this->boolean()->notNull()->defaultValue(false)->comment('Удалено')
		);
	}

}
