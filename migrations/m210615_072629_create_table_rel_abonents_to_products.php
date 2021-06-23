<?php
declare(strict_types = 1);
use pozitronik\core\models\RelationMigration;

/**
* Class m210615_072629_create_table_rel_abonents_to_products
*/
class m210615_072629_create_table_rel_abonents_to_products extends RelationMigration {
	public string $table_name = 'relation_abonents_to_products';
	public string $first_key = 'abonent_id';
	public string $second_key = 'product_id';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		parent::safeUp();
		$this->addForeignKey('fk_ratp_to_abonents', 'relation_abonents_to_products', 'abonent_id', 'abonents', 'id');
		$this->addForeignKey('fk_ratp_to_products', 'relation_abonents_to_products', 'product_id', 'products', 'id');
	}
}
