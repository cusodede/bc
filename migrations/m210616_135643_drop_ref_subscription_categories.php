<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210616_135643_drop_ref_subscription_categories
*/
class m210616_135643_drop_ref_subscription_categories extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropForeignKey('fk-subscriptions-category_id', 'subscriptions');
		$this->dropColumn('subscriptions', 'category_id');
		$this->dropTable('ref_subscription_categories');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->createTable('ref_subscription_categories', [
			'id' => $this->primaryKey(),
			'name' => $this->string(64)->comment('Наименование категории'),
			'deleted' => $this->boolean()->notNull()->defaultValue(0)->comment('Флаг активности'),
		]);
		$this->addColumn('subscriptions', 'category_id', $this->integer()->notNull()->comment('id категории подписки'));
	}
}
