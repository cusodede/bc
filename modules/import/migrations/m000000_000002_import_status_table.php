<?php
declare(strict_types = 1);

namespace app\modules\import\migrations;

use app\modules\import\models\ImportModel;
use yii\db\Migration;

/**
 * Class m000000_000002_import_status_table
 */
class m000000_000002_import_status_table extends Migration {
	private const TABLE_NAME = 'sys_import_status';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'model' => $this->string()->notNull(),
			'domain' => $this->integer()->notNull(),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Время создания импорта'),
			'filename' => $this->string()->null()->comment('Имя загруженного для импорта файла'),
			'status' => $this->integer()->notNull()->comment('Статус')->defaultValue(ImportModel::STATUS_REGISTERED),
			'processed' => $this->integer()->null()->comment('Строк загружено для разбора'),
			'skipped' => $this->integer()->null()->comment('Строк пропущено'),
			'imported' => $this->integer()->null()->comment('Строк импортировано'),
			'error' => $this->text()

		]);

		$this->createIndex(self::TABLE_NAME.'_domain', self::TABLE_NAME, 'domain');
		$this->createIndex(self::TABLE_NAME.'_model', self::TABLE_NAME, 'model');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable(self::TABLE_NAME);
	}
}
