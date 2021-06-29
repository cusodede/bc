<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\subscriptions\Subscriptions;
use app\models\products\Products;
use app\models\products\EnumProductsPaymentPeriods;

/**
* Class m210629_061556_refactor_subscription_units
*/
class m210629_061556_refactor_subscription_units extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropColumn(Subscriptions::tableName(), 'units');
		$this->addColumn(Subscriptions::tableName(), 'units', $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('Единица измерения триального периода'));
		Products::updateAll(['payment_period' => EnumProductsPaymentPeriods::TYPE_MONTHLY]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(Subscriptions::tableName(), 'units');
		$this->addColumn(Subscriptions::tableName(), 'units', $this->string(10)->notNull()->defaultValue('days')->comment('Единица измерения триального периода'));
	}

}
