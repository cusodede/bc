<?php
declare(strict_types = 1);
use app\components\db\Migration;

/**
 * Class m210712_215540_sys_permissions_module_support
 */
class m210712_215540_sys_permissions_module_support extends Migration {
	private const TABLE_NAME = 'sys_permissions';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn(self::TABLE_NAME, 'module', $this->string()->after('verb')->null());
		$this->createIndex(self::TABLE_NAME.'_module', self::TABLE_NAME, ['module']);/*Составного ключа не получится, вылезаем за размеры*/
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex(self::TABLE_NAME.'_module', self::TABLE_NAME);
		$this->dropColumn(self::TABLE_NAME, 'module');
	}

}
