<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Handles the creation of table contracts.
 */
class m210825_120221_create_contracts_table extends Migration
{
	private const TABLE_NAME = 'contracts';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'contract_number' => $this->string(64)->notNull()->comment('№ договора'),
			'contract_number_nfs' => $this->string(64)->notNull()->comment('№ контракта'),
			'signing_date' => $this->dateTime()->comment('Дата подписания договора'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания договора'),
			'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления договора'),
		]);

		$this->createIndex('idx-contracts-numbers', 'contracts', ['contract_number', 'contract_number_nfs']);
		$this->createIndex('idx-contracts-deleted', 'contracts', 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable(self::TABLE_NAME);
	}
}
