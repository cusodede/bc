<?php
declare(strict_types = 1);
use app\components\db\Migration;
use app\models\abonents\Abonents;

/**
* Class m210526_093435_add_abonents
*/
class m210526_093435_add_abonents extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable(Abonents::tableName(), [
			'id' => $this->primaryKey(),
			'surname' => $this->string(64)->comment('Фамилия абонента'),
			'name' => $this->string(64)->comment('Имя абонента'),
			'patronymic' => $this->string(64)->comment('Отчество абонента'),
			'phone' => $this->string(11)->notNull()->comment('Номер абонента'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)->comment('Флаг активности'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата создания абонента'),
			'updated_at' => $this->timestamp()->notNull()->comment('Дата обновления абонента'),
		]);

		switch ($this->db->driverName) {
			case 'mysql':
				$this->alterColumn(Abonents::tableName(), 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления абонента'));
			break;
			case 'pgsql':
				$this->createOnUpdateTrigger(Abonents::tableName());
			break;
		}

		$this->createIndex('idx-abonents-deleted', 'abonents', 'deleted');
		$this->createIndex('idx-abonents-phone', 'abonents', 'phone', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex('idx-abonents-deleted', 'abonents');
		$this->dropIndex('idx-abonents-phone', 'abonents');
		$this->dropTable('abonents');
	}

}
