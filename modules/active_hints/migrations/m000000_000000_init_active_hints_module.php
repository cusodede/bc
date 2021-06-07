<?php
declare(strict_types = 1);

namespace app\modules\active_hints\migrations;

use app\modules\active_hints\ActiveHintsModule;
use yii\db\Migration;

/**
 * Class m000000_000000_init_active_hints_module
 */
class m000000_000000_init_active_hints_module extends Migration {
	/**
	 * @var string|null
	 */
	private ?string $tableName = null;

	public function init() {
		$this->tableName = ActiveHintsModule::getConfigParameter('tableName');
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable($this->tableName, [
			'id' => $this->primaryKey(),
			'at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
			'user' => $this->integer()->null()->defaultValue(null),
			'model' => $this->string(512)->notNull(),
			'attribute' => $this->string()->notNull(),
			'header' => $this->string()->null(),
			'content' => $this->text()->null(),
			'placement' => $this->integer()->null()
		]);

		$this->createIndex('model_attribute', $this->tableName, ['model', 'attribute'], true);
		$this->createIndex('user', $this->tableName, 'user');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
