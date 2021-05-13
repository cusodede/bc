<?php
declare(strict_types = 1);
use pozitronik\core\models\RelationMigration;

/**
 * Class m210512_104707_RelStoresToMerch
 */
class m210512_104707_RelStoresToMerch extends RelationMigration {

	public string $table_name = "relation_stores_to_merch";
	public string $first_key = "store_id";
	public string $second_key = "merch_id";

}
