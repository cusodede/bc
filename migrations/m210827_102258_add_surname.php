<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210827_102258_add_surname
*/
class m210827_102258_add_surname extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('sys_users', 'surname', $this->string(255)->after('username')->comment('Фамилия пользователя'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('sys_users', 'surname');
	}
}
