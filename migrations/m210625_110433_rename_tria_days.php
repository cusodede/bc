<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\subscriptions\Subscriptions;

/**
* Class m210625_110433_rename_tria_days
*/
class m210625_110433_rename_tria_days extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->renameColumn(Subscriptions::tableName(), 'trial_days_count', 'trial_count');
		$this->addColumn(Subscriptions::tableName(), 'units', $this->string(10)->notNull()->defaultValue('days')->comment('Единица измерения триального периода'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->renameColumn(Subscriptions::tableName(), 'trial_count', 'trial_days_count');
		$this->dropColumn(Subscriptions::tableName(), 'units');
	}

}
