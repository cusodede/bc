<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210830_153612_drop_username
*/
class m210830_153612_drop_username extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropColumn('sys_users', 'username');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->addColumn('sys_users', 'username', $this->string(255)->notNull()->comment('Отображаемое имя пользователя')->after('id'));
		$this->createIndex('username', 'sys_users', 'username');
	}
}
