<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\subscriptions\Subscriptions;

/**
* Class m210608_195911_drop_trial
*/
class m210608_195911_drop_trial extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropIndex('idx-subscriptions-trial', Subscriptions::tableName());
		$this->dropColumn(Subscriptions::tableName(), 'trial');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->addColumn(Subscriptions::tableName(), 'trial', $this->boolean()->notNull()->defaultValue(false)->comment('Триальный период'));
		$this->createIndex('idx-subscriptions-trial', Subscriptions::tableName(), 'trial');
	}

}
