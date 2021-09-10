<?php
declare(strict_types = 1);
use app\models\sys\users\active_record\Users;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m210805_084918_deployment_default_admin_creation
 */
class m210805_084918_deployment_default_admin_creation extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		if (0 === (int)Users::find()->count()) {
			$this->insert(Users::tableName(), [
				'id' => 1,
				'username' => 'admin',
				'login' => 'admin',
				'password' => 'admin',
				'is_pwd_outdated' => true, /*require to update admin password*/
				'email' => 'admin@localhost.ru',
				'comment' => 'Системный администратор',
				'create_date' => new Expression('NOW()')
			]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		/*Это миграция на автоматическое создание админа после первого деплоя, она не не откатывается*/
	}

}
