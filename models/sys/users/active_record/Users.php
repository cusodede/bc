<?php
declare(strict_types = 1);

namespace app\models\sys\users\active_record;

use app\models\phones\active_record\PhonesAR;
use app\models\sys\users\active_record\relations\RelUsersToPhones;
use app\modules\history\behaviors\HistoryBehavior;
use pozitronik\helpers\DateHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_users".
 *
 * @property int $id
 * @property string $username Отображаемое имя пользователя
 * @property string $login Логин
 * @property string $password Хеш пароля либо сам пароль (если $salt пустой)
 * @property null|string $restore_code Код восстановления пароля, если запрошен
 * @property null|string $salt Unique random salt hash
 * @property bool $is_pwd_outdated Пароль должен быть сменён пользователем
 * @property string $email email
 * @property string $comment Служебный комментарий пользователя
 * @property string $create_date Дата регистрации
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property bool $deleted Флаг удаления
 *
 * @property UsersTokens[] $relatedUsersTokens Связанные с моделью пользователя модели токенов
 * @property RelUsersToPhones[] $relatedUsersToPhones Связь к промежуточной таблице к телефонным номерам
 * @property PhonesAR[] $relatedPhones Телефонные номера пользователя
 */
class Users extends ActiveRecord {

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			'history' => [
				'class' => HistoryBehavior::class
			]
		];
	}

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
			[['daddy'], 'integer'],
			[['deleted', 'is_pwd_outdated'], 'boolean'],
			[['deleted', 'is_pwd_outdated'], 'default', 'value' => false],
			[['username', 'password', 'salt', 'email'], 'string', 'max' => 255],
			[['restore_code'], 'string', 'max' => 40],
			[['login'], 'string', 'max' => 64],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['daddy'], 'default', 'value' => Yii::$app->user->id],
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
			'restore_code' => 'Код восстановления',
			'salt' => 'Соль',
			'is_pwd_outdated' => 'Пользователь должен сменить пароль при входе',
			'email' => 'Почтовый адрес',
			'comment' => 'Служебный комментарий пользователя',
			'create_date' => 'Дата регистрации',
			'daddy' => 'ID зарегистрировавшего/проверившего пользователя',
			'deleted' => 'Флаг удаления',
			'update_password' => 'Новый пароль'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersTokens():ActiveQuery {
		return $this->hasMany(UsersTokens::class, ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersToPhones():ActiveQuery {
		return $this->hasMany(RelUsersToPhones::class, ['user_id' => 'id']);
	}

	/**
	 * @return PhonesAR[]
	 */
	public function getRelatedPhones():array {
		return $this->hasMany(PhonesAR::class, ['id' => 'phone_id'])->via('relatedUsersToPhones');
	}

}
