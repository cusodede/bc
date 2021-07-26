<?php
declare(strict_types = 1);

use yii\db\Migration;
use yii\db\Exception;
use app\models\partners\Partners;
use app\models\common\RefPartnersCategories;

/**
* Class m210616_143150_add_category_id_to_partners
*/
class m210616_143150_add_category_id_to_partners extends Migration
{
	/**
	 * {@inheritdoc}
	 * @throws Exception
	 */
	public function safeUp()
	{
		Yii::$app->db->createCommand()->insert(RefPartnersCategories::tableName(), ['id' => 1, 'name' => 'Видеосервисы'])->execute();
		$this->addColumn(Partners::tableName(), 'category_id', $this->integer()->notNull()->comment('id категории партнера')->after('inn'));
		Partners::updateAll(['category_id' => 1]);
		$this->addForeignKey('fk-partners-category_id', 'partners', 'category_id', RefPartnersCategories::tableName(), 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk-partners-category_id', RefPartnersCategories::tableName());
		$this->dropColumn(Partners::tableName(), 'category_id');
	}

}
