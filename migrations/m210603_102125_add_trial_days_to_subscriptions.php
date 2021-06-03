<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\subscriptions\Subscriptions;

/**
* Class m210603_102125_add_trial_days_to_subscriptions
*/
class m210603_102125_add_trial_days_to_subscriptions extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(Subscriptions::tableName(), 'trial_days_count', $this->integer()->notNull()->defaultValue(0));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(Subscriptions::tableName(), 'trial_days_count');
	}
}
