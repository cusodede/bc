<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Handles the creation of table refsharing_rates.
 */
class m210825_140829_create_refsharing_rates_table extends Migration
{
	private const TABLE_NAME = 'refsharing_rates';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'description' => $this->string(255)->notNull()->comment('Описание процентной ставки'),
			'calc_formula' => $this->string(255)->notNull()->comment('Формула расчета'),
			'value' => $this->integer()->notNull()->comment('Значение процентной ставки'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания договора'),
			'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления договора'),
		]);
		
		$this->createIndex('idx-refsharing-deleted', 'refsharing_rates', 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable(self::TABLE_NAME);
	}
}
