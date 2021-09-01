<?php
declare(strict_types = 1);

namespace app\modules\import\migrations;

use yii\db\Migration;

/**
 * Class m000000_000001_fix_processed_field_type
 */
class m000000_000001_fix_processed_field_type extends Migration {
	private const TABLE_NAME = 'sys_import';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn(self::TABLE_NAME, 'processed');
		$this->addColumn(self::TABLE_NAME, 'processed', $this->integer()->notNull()->defaultValue(0));
		$this->createIndex('processed', self::TABLE_NAME, 'processed');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn(self::TABLE_NAME, 'processed');
		$this->addColumn(self::TABLE_NAME, 'processed', $this->boolean()->notNull()->defaultValue(false));
		$this->createIndex('processed', self::TABLE_NAME, 'processed');
	}
}
