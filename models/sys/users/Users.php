<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use pozitronik\core\traits\ARExtended;
use pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_users".
 *
 * @property int $id
 * @property string $username Отображаемое имя пользователя
 * @property string $login Логин
 * @property string $password Хеш пароля
 * @property null|string $salt Unique random salt hash
 * @property string $email email
 * @property string $comment Служебный комментарий пользователя
 * @property string $create_date Дата регистрации
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property bool $deleted Флаг удаления
 *
 * @property int|null $position Должность/позиция
 *
 * @property-read string $authKey

 * *************************
 * todo: если salt пустой, то разрешаем вход по логину/паролю
 */
class Users extends ActiveRecord {
	use ARExtended;

	public const PROFILE_IMAGE_DIRECTORY = '@app/web/profile_photos/';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['username', 'login', 'password', 'email'], 'required'],//Не ставим create_date как required, поле заполнится default-валидатором (а если нет - отвалится при инсерте в базу)
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['daddy', 'position'], 'integer'],
			[['deleted'], 'boolean'],
			[['deleted'], 'default', 'value' => false],
			[['username', 'password', 'salt', 'email'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 64],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['daddy'], 'default', 'value' => CurrentUser::Id()],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]//default-валидатор срабатывает только на незаполненные атрибуты, его нельзя использовать как обработчик любых изменений атрибута
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'username' => 'Имя пользователя',
			'login' => 'Логин',
			'password' => 'Пароль',
			'salt' => 'Unique random salt hash',
			'email' => 'Почтовый адрес',
			'comment' => 'Служебный комментарий пользователя',
			'create_date' => 'Дата регистрации',
			'daddy' => 'ID зарегистрировавшего/проверившего пользователя',
			'deleted' => 'Флаг удаления',
		];
	}

	/**
	 * @param string $login
	 * @return Users|null
	 */
	public static function findByLogin(string $login):?Users {
		return self::findOne(['login' => $login]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeValidate():bool {
		if ($this->isNewRecord) {
			if (null === $this->salt) {
				$this->salt = sha1(uniqid((string)mt_rand(), true));
				$this->password = sha1($this->password.$this->salt);
			}
		} elseif (!empty($this->update_password)) {
			$this->salt = sha1(uniqid((string)mt_rand(), true));
			$this->password = sha1($this->update_password.$this->salt);
		}
		return parent::beforeValidate();
	}

	/**
	 * @return string
	 */
	public function getAuthKey():string {
		return md5($this->id.md5($this->login));
	}

}
