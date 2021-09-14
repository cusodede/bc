<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210913_085040_edit_partner_id_users
*/
class m210913_085040_edit_partner_id_users extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->alterColumn('sys_users', 'partner_id', $this->integer()->defaultValue(null)->comment('Привязка пользователя к партнёру'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->alterColumn('sys_users', 'partner_id', $this->integer()->notNull()->defaultValue(0)->comment('Привязка пользователя к партнёру'));
	}

}
