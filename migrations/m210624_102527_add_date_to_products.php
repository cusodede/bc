<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\products\Products;

/**
* Class m210624_102527_add_date_to_products
*/
class m210624_102527_add_date_to_products extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(Products::tableName(), 'start_date', $this->dateTime()->defaultValue(null)->after('partner_id')->comment('Дата начала действия продукта'));
		$this->addColumn(Products::tableName(), 'end_date', $this->dateTime()->defaultValue(null)->after('start_date')->comment('Дата окончания действия продукта'));
		$this->addColumn(Products::tableName(), 'payment_period', $this->tinyInteger(1)->defaultValue(0)->after('end_date')->comment('Периодичность списания'));
		$this->createIndex('idx-partners-payment_period', Products::tableName(), 'payment_period');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex('idx-partners-payment_period', Products::tableName());
		$this->dropColumn(Products::tableName(), 'start_date');
		$this->dropColumn(Products::tableName(), 'end_date');
		$this->dropColumn(Products::tableName(), 'payment_period');
	}

}
