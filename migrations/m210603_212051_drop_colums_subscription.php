<?php
declare(strict_types = 1);

use app\models\subscriptions\Subscriptions;

/**
* Class m210603_212051_drop_colums_subscription
*/
class m210603_212051_drop_colums_subscription extends \app\components\db\Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropForeignKey('fk-subscriptions-user_id', Subscriptions::tableName());
		$this->dropIndex('idx-subscriptions-deleted', Subscriptions::tableName());
		$this->dropColumn(Subscriptions::tableName(), 'user_id');
		$this->dropColumn(Subscriptions::tableName(), 'deleted');
		$this->dropColumn(Subscriptions::tableName(), 'created_at');
		$this->dropColumn(Subscriptions::tableName(), 'updated_at');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->addColumn(Subscriptions::tableName(), 'user_id', $this->integer()->notNull()->comment('id пользователя, создателя')->defaultValue(1));
		$this->addColumn(Subscriptions::tableName(), 'deleted', $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'));
		$this->addColumn(Subscriptions::tableName(), 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания подписки'));

		switch ($this->db->driverName) {
			case 'mysql':
				$this->addColumn(Subscriptions::tableName(), 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления подписки'));
			break;
			case 'pgsql':
				$this->addColumn(Subscriptions::tableName(), 'updated_at', $this->timestamp()->comment('Дата обновления подписки'));
				if (!$this->createOnUpdateTrigger(Subscriptions::tableName())) {
					throw new \yii\db\Exception('Не удалось создать триггер для таблицы ' . Subscriptions::tableName());
				}
			break;
		}

		$this->addForeignKey('fk-subscriptions-user_id', Subscriptions::tableName(), 'user_id', 'sys_users', 'id', 'CASCADE');
		$this->createIndex('idx-subscriptions-deleted', Subscriptions::tableName(), 'deleted');
	}

}
