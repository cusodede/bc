<?php
declare(strict_types = 1);

use app\components\db\Migration;

/**
* Class m210902_144812_rename_table_refsharing
*/
class m210902_144812_rename_table_refsharing extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropTable('refsharing_rates');

		$this->createTable('revshare_rates', [
			'id' => $this->primaryKey(),
			'type' => $this->smallInteger()->notNull(),
			'rate' => $this->decimal(3, 2)->notNull()->comment('Процентная ставка'),
			'condition_value' => $this->integer()->notNull()->comment('Пороговое значение для активации ставки'),
			'product_id' => $this->integer()->notNull(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
			'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
		]);

		$this->addForeignKey('fk_revshare_$product_id', 'revshare_rates', 'product_id', 'products', 'id', 'CASCADE', 'CASCADE');

		$this->createIndex('in_revshare_$deleted', 'revshare_rates', 'deleted');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('revshare_rates');


		$this->createTable('refsharing_rates', [
			'id' => $this->primaryKey(),
			'type' => $this->integer()->notNull()->after('id')->comment('Тип ставки'),
			'ref_share' => $this->float()->notNull()->after('type')->comment('Ставка рефшеринга'),
			'value' => $this->integer()->notNull()->comment('Значение процентной ставки'),
			'product_id' => $this->integer()->notNull()->after('id')->comment('id продукта'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания договора'),
			'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления договора'),
		]);

		$this->addForeignKey('fk_ref_share_product_id', 'refsharing_rates', 'product_id', 'products', 'id', 'CASCADE');

		$this->createIndex('idx-refsharing-deleted', 'refsharing_rates', 'deleted');
	}

}
