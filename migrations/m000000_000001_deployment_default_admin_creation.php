<?php
declare(strict_types = 1);
use app\models\sys\users\active_record\Users;
use yii\db\Expression;
use app\components\db\Migration;

/**
 * Class m000000_000001_deployment_default_admin_creation
 */
class m000000_000001_deployment_default_admin_creation extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->insert(Users::tableName(), [
			'username' => 'admin',
			'login' => 'admin',
			'password' => 'admin',
			'is_pwd_outdated' => 1, /*require to update admin password*/
			'email' => 'admin@localhost.ru',
			'comment' => 'Системный администратор',
			'create_date' => new Expression('NOW()')
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		/*Это миграция на автоматическое создание админа после первого деплоя, она не не откатывается*/
	}

}
