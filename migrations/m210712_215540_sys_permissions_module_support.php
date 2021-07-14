<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
 * Class m210712_215540_sys_permissions_module_support
 */
class m210712_215540_sys_permissions_module_support extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_permissions', 'module', $this->string()->after('verb')->null());
		$this->createIndex('module', 'sys_permissions', ['module']);/*Составного ключа не получится, вылезаем за размеры*/
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('module', 'sys_permissions');
		$this->dropColumn('sys_permissions', 'module');
	}

}
