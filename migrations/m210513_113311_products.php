<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
* Class m210513_113311_products
*/
class m210513_113311_products extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('products', [
			'id' => $this->primaryKey(),
			'name' => $this->string(64)->notNull()->comment('Название продукта'),
			'description' => $this->string(255)->comment('Описание продукта'),
			'type_id' => $this->integer()->comment('id типа (подписка, бандл и т.д)'),
			'user_id' => $this->integer()->notNull()->comment('id пользователя, создателя'),
			'partner_id' => $this->integer()->notNull()->comment('id партнера, к кому привязан'),
			'deleted' => $this->boolean()->notNull()->defaultValue(0)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания партнера'),
		]);

		$this->createIndex('idx-products-user_id', 'products', 'user_id');
		$this->addForeignKey('fk-products-user_id', 'products', 'user_id', 'sys_users', 'id', 'CASCADE');

		$this->createIndex('idx-products-type_id', 'products', 'type_id');
		$this->addForeignKey('fk-products-type_id', 'products', 'type_id', 'ref_products_types', 'id', 'CASCADE');

		$this->createIndex('idx-products-partner_id', 'products', 'partner_id');
		$this->addForeignKey('fk-products-partner_id', 'products', 'partner_id', 'partners', 'id', 'CASCADE');

		$this->createIndex('idx-products-deleted', 'products', 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk-products-user_id', 'products');
		$this->dropForeignKey('fk-products-type_id', 'products');
		$this->dropForeignKey('fk-products-partner_id', 'products');

		$this->dropIndex('idx-products-user_id', 'products');
		$this->dropIndex('idx-products-type_id', 'products');
		$this->dropIndex('idx-products-partner_id', 'products');
		$this->dropIndex('idx-products-deleted', 'products');

		$this->dropTable('products');
	}
}
