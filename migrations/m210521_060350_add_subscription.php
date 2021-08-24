<?php
declare(strict_types = 1);

use app\models\subscriptions\Subscriptions;

/**
* Class m210521_060350_add_subscription
*/
class m210521_060350_add_subscription extends \app\components\db\Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable(Subscriptions::tableName(), [
			'id' => $this->primaryKey(),
			'product_id' => $this->integer()->notNull()->comment('id продукта'),
			'category_id' => $this->integer()->notNull()->comment('id категории подписки'),
			'user_id' => $this->integer()->notNull()->comment('id пользователя, создателя'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания партнера'),
			'updated_at' => $this->timestamp()->notNull()->comment('Дата обновления подписки'),
		]);

		switch ($this->db->driverName) {
			case 'mysql':
				$this->alterColumn(Subscriptions::tableName(), 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления подписки'));
			break;
			case 'pgsql':
				$this->alterColumn(Subscriptions::tableName(), 'updated_at', $this->timestamp()->comment('Дата обновления подписки'));
				if (!$this->createOnUpdateTrigger(Subscriptions::tableName())) {
					throw new \yii\db\Exception('Не удалось создать триггер для таблицы ' . Partners::tableName());
				}
			break;
		}

		$this->addForeignKey('fk-subscriptions-category_id', 'subscriptions', 'category_id', 'ref_subscription_categories', 'id', 'CASCADE');
		$this->addForeignKey('fk-subscriptions-user_id', 'subscriptions', 'user_id', 'sys_users', 'id', 'CASCADE');
		$this->addForeignKey('fk-subscriptions-product_id', 'subscriptions', 'product_id', 'products', 'id', 'CASCADE');
		$this->createIndex('idx-subscriptions-deleted', 'subscriptions', 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk-subscriptions-category_id', 'subscriptions');
		$this->dropForeignKey('fk-subscriptions-user_id', 'subscriptions');
		$this->dropForeignKey('fk-subscriptions-product_id', 'subscriptions');
		$this->dropIndex('idx-subscriptions-deleted', 'subscriptions');
		$this->dropTable('subscriptions');
	}

}
