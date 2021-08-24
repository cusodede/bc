<?php
declare(strict_types = 1);
use app\components\db\Migration;
use app\models\partners\Partners;

/**
* Class m210517_054125_add_to_partners
*/
class m210517_054125_add_to_partners extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{


		$this->update_updated_at_column();

		$this->addColumn(Partners::tableName(), 'inn', $this->string(12)->notNull()->after('name')->comment('ИНН партнера'));
		$this->createIndex('idx-partners-inn', Partners::tableName(), 'inn', true);

		switch ($this->db->driverName) {
			case 'mysql':
				$this->addColumn(Partners::tableName(), 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления партнера'));
			break;
			case 'pgsql':
				$this->addColumn(Partners::tableName(), 'updated_at', $this->timestamp()->comment('Дата обновления партнера'));
				$this->createOnUpdateTrigger(Partners::tableName());
			break;
		}

		$this->createIndex('idx-partners-name', Partners::tableName(), 'name');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(Partners::tableName(), 'updated_at');
		$this->dropIndex('idx-partners-inn', Partners::tableName());
		$this->dropIndex('idx-partners-name', Partners::tableName());
		$this->dropColumn(Partners::tableName(), 'inn');
	}

}
