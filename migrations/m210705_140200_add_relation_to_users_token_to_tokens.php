<?php
declare(strict_types = 1);
use pozitronik\relations\models\RelationMigration;

/**
* Class m210705_140200_add_relation_to_users_token_to_tokens
*/
class m210705_140200_add_relation_to_users_token_to_tokens extends RelationMigration {
	public string $table_name = 'sys_relation_users_tokens_to_tokens';
	public string $first_key = 'parent_id';
	public string $second_key = 'child_id';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		parent::safeUp();
		$this->addForeignKey('fk_rel_tokens_to_parent_token', $this->table_name, 'parent_id', 'sys_users_tokens', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_rel_tokens_to_child_token', $this->table_name, 'child_id', 'sys_users_tokens', 'id', 'CASCADE', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropForeignKey('fk_rel_tokens_to_child_token', $this->table_name);
		$this->dropForeignKey('fk_rel_tokens_to_parent_token', $this->table_name);
		parent::safeDown();
	}

}
