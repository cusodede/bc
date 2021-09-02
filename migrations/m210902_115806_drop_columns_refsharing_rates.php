<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
* Class m210902_115806_drop_columns_refsharing_rates
*/
class m210902_115806_drop_columns_refsharing_rates extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropColumn('refsharing_rates', 'description');
		$this->dropColumn('refsharing_rates', 'calc_formula');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->addColumn('refsharing_rates', 'description', $this->string(255)->notNull()->after('id')->comment('Описание процентной ставки'));
		$this->addColumn('refsharing_rates', 'calc_formula', $this->string(255)->notNull()->after('description')->comment('Формула расчета'));

	}

}
