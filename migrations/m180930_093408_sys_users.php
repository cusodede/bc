<?php
declare(strict_types = 1);

use app\components\db\Migration;
use app\models\sys\users\active_record\Users;
use yii\db\Expression;

/**
 * Class m180930_093408_sys_users
 */
class m180930_093408_sys_users extends Migration {
	private const TABLE_NAME = 'sys_users';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'username' => $this->string(255)->notNull()->comment('Отображаемое имя пользователя'),
			'login' => $this->string(64)->notNull()->comment('Логин'),
			'password' => $this->string(255)->notNull()->comment('Хеш пароля'),
			'salt' => $this->string(255)->null()->comment('Unique random salt hash'),
			'email' => $this->string(255)->notNull()->comment('email'),
			'comment' => $this->text()->null()->comment('Служебный комментарий пользователя'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата регистрации'),
			'daddy' => $this->integer()->null()->comment('ID зарегистрировавшего/проверившего пользователя'),
			'deleted' => $this->boolean()->defaultValue(false)->comment('Флаг удаления')
		]);

		$this->addColumn(self::TABLE_NAME, 'is_pwd_outdated', $this->boolean()->notNull()->defaultValue(false)->after('salt')->comment('Ожидается смена пароля'));
		$this->addColumn(self::TABLE_NAME, 'restore_code', $this->string('40')->null()->after('salt')->comment('Код восстановления'));

		$this->createIndex(self::TABLE_NAME.'_username', self::TABLE_NAME, 'username');
		$this->createIndex(self::TABLE_NAME.'_login', self::TABLE_NAME, 'login', true);
		$this->createIndex(self::TABLE_NAME.'_email', self::TABLE_NAME, 'email', true);
		$this->createIndex(self::TABLE_NAME.'_daddy', self::TABLE_NAME, 'daddy');
		$this->createIndex(self::TABLE_NAME.'_deleted', self::TABLE_NAME, 'deleted');

		$this->createIndex(self::TABLE_NAME.'_is_pwd_outdated', self::TABLE_NAME, 'is_pwd_outdated');

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
		$this->dropTable(self::TABLE_NAME);
	}

}
