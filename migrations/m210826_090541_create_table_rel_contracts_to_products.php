<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
* Class m210826_090541_create_table_rel_contracts_to_products
*/
class m210826_090541_create_table_rel_contracts_to_products extends Migration {
	private const TABLE_NAME = 'relation_contracts_to_products';


	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'contract_id' => $this->integer()->notNull()->comment('id договора'),
			'product_id' => $this->integer()->notNull()->comment('id продукта'),
		]);

		$this->addForeignKey('fk_rel_tab_to_contracts', 'relation_contracts_to_products', 'contract_id', 'contracts', 'id');
		$this->addForeignKey('fk_rel_tab_to_products', 'relation_contracts_to_products', 'product_id', 'products', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk_rel_tab_to_contracts', 'contracts');
		$this->dropForeignKey('fk_rel_tab_to_products', 'products');
		$this->dropTable(self::TABLE_NAME);
	}

}
