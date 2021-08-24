<?php
declare(strict_types = 1);
use yii\db\Migration;
use app\models\partners\Partners;
use yii\db\Exception;

/**
* Class m210517_054125_add_to_partners
*/
class m210517_054125_add_to_partners extends \app\components\db\Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$function = <<< SQL
CREATE FUNCTION update_updated_at_column()
    RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = now();
    RETURN NEW;
END;
$$ language 'plpgsql';
SQL;
		$this->execute($function);

		$this->addColumn(Partners::tableName(), 'inn', $this->string(12)->notNull()->after('name')->comment('ИНН партнера'));
		$this->createIndex('idx-partners-inn', Partners::tableName(), 'inn', true);

		switch ($this->db->driverName) {
			case 'mysql':
				$this->addColumn(Partners::tableName(), 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('Дата обновления партнера'));
			break;
			case 'pgsql':
				$this->addColumn(Partners::tableName(), 'updated_at', $this->timestamp()->comment('Дата обновления партнера'));
				if (!$this->createOnUpdateTrigger(Partners::tableName())) {
					throw new Exception('Не удалось создать триггер для таблицы ' . Partners::tableName());
				}
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
