<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210830_112712_add_name_to_users
*/
class m210830_112712_add_name_to_users extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('sys_users', 'name', $this->string(255)->after('username')->comment('Имя пользователя'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('sys_users', 'name');
	}

}
