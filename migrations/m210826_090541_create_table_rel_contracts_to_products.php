<?php
declare(strict_types = 1);

use pozitronik\relations\models\RelationMigration;

/**
 * Class m210826_090541_create_table_rel_contracts_to_products
 */
class m210826_090541_create_table_rel_contracts_to_products extends RelationMigration
{
	public string $table_name = 'relation_contracts_to_products';
	public string $first_key = 'contract_id';
	public string $second_key = 'product_id';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		parent::safeUp();
		$this->addForeignKey('fk_rel_tab_to_contracts', 'relation_contracts_to_products', 'contract_id', 'contracts', 'id');
		$this->addForeignKey('fk_rel_tab_to_products', 'relation_contracts_to_products', 'product_id', 'products', 'id');
	}
}
