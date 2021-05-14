<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
* Class m210513_112805_partners
*/
class m210513_112805_partners extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('partners', [
			'id' => $this->primaryKey(),
			'name' => $this->string(64)->notNull()->comment('Название партнера'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания партнера'),
			'deleted' => $this->boolean()->notNull()->defaultValue(0)->comment('Флаг активности'),
		]);

		$this->createIndex('idx-partners-deleted', 'partners', 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex('idx-partners-deleted', 'partners');
		$this->dropTable('partners');
	}

}
