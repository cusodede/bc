<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
 * Class m210819_122321_SetDefaultPermissionCollection
 */
class m210819_122321_SetDefaultPermissionCollection extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_permissions_collections', 'default', $this->boolean()->defaultValue(false)->notNull()->comment('Включение группы по умолчанию'));

		$this->createIndex('sys_permissions_collections_default', 'sys_permissions_collections', 'default');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('default', 'sys_permissions_collections');
	}

}
