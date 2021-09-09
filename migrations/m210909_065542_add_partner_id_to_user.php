<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210909_065542_add_partner_id_to_user
*/
class m210909_065542_add_partner_id_to_user extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('sys_users', 'partner_id', $this->integer()->notNull()->defaultValue(0)->comment('Привязка пользователя к партнёру'));
		$this->createIndex('idx-sys_users-partner_id', 'sys_users', 'partner_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex('idx-sys_users-partner_id', 'sys_users');
		$this->dropColumn('sys_users', 'partner_id');
	}

}
