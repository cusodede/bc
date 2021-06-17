<?php
declare(strict_types = 1);

use yii\db\Migration;
use app\models\partners\Partners;

/**
* Class m210617_080336_add_partners_attribute
*/
class m210617_080336_add_partners_attribute extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn(Partners::tableName(), 'phone', $this->string(11)->null()->after('category_id')->comment('Телефон поддержки партнера'));
		$this->addColumn(Partners::tableName(), 'email', $this->string(255)->null()->after('phone')->comment('Почтовый адрес поддержки партнера'));
		$this->addColumn(Partners::tableName(), 'comment', $this->text()->null()->comment('Комментарий'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn(Partners::tableName(), 'phone');
		$this->dropColumn(Partners::tableName(), 'email');
		$this->dropColumn(Partners::tableName(), 'comment');
	}

}
