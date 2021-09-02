<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
* Class m210902_123541_add_colums_refsharing_rates
*/
class m210902_123541_add_colums_refsharing_rates extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('refsharing_rates', 'type', $this->integer()->notNull()->after('id')->comment('Тип ставки'));
		$this->addColumn('refsharing_rates', 'ref_share', $this->float()->notNull()->after('type')->comment('Ставка рефшеринга'));

		$this->createIndex('idx_value_type_ref_share', 'refsharing_rates', ['value', 'type', 'ref_share']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('refsharing_rates', 'type');
		$this->dropColumn('refsharing_rates', 'ref_share');
	}

}
