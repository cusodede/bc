<?php
declare(strict_types = 1);

namespace app\models\sys\users\active_record;

use app\components\db\ActiveRecordTrait;
use app\components\validators\PasswordStrengthValidator;
use app\models\common\RefPartnersCategories;
use app\models\partners\Partners;
use app\models\phones\active_record\PhonesAR;
use app\models\phones\PhoneNumberValidator;
use app\models\phones\Phones;
use app\models\products\Products;
use app\models\sys\users\active_record\relations\RelUsersToPhones;
use app\modules\history\behaviors\HistoryBehavior;
use pozitronik\helpers\DateHelper;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sys_users".
 *
 * @property int $id
 * @property string $name Имя пользователя
 * @property string $surname Фамилия пользователя
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
 * @property int $partner_id Идентификатор партнёра
 *
 * @property-read UsersTokens[] $relatedUsersTokens Связанные с моделью пользователя модели токенов
 * @property-read string $username ФИО пользователя
 * @property RelUsersToPhones[] $relatedUsersToPhones Связь к промежуточной таблице к телефонным номерам
 * @property PhonesAR[] $relatedPhones Телефонные номера пользователя (таблица)
 * @property string[] $phones Виртуальный атрибут: телефонные номера в строковом массиве, используется для редактирования
 */
class Users extends ActiveRecord
{
	use ActiveRecordTrait;

	private ?array $_phones = null;

	/**
	 * @inheritDoc
	 */
	public function behaviors(): array
	{
		return [
			'history' => [
				'class' => HistoryBehavior::class
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'sys_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['surname', 'name', 'login', 'password', 'email'], 'required'],//Не ставим create_date как required, поле заполнится default-валидатором (а если нет - отвалится при инсерте в базу)
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['daddy', 'partner_id'], 'integer'],
			[['deleted', 'is_pwd_outdated'], 'boolean'],
			[['deleted', 'is_pwd_outdated'], 'default', 'value' => false],
			[['name', 'surname', 'password', 'salt', 'email'], 'string', 'max' => 255],
			[['password'], PasswordStrengthValidator::class, 'when' => function(self $model) {
				//Если пароль подсолен, валидация вернет ошибку, поэтому валидируем только при изменении.
				return $model->isAttributeUpdated('password');
			}],
			[['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::class, 'targetAttribute' => ['partner_id' => 'id']],
			[['partner_id'], 'default', 'value' => 0],
			[['restore_code'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 64],
			[['name', 'surname'], 'string', 'min' => 3],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['daddy'], 'default', 'value' => Yii::$app->user->id],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()],//default-валидатор срабатывает только на незаполненные атрибуты, его нельзя использовать как обработчик любых изменений атрибута
			['phones', PhoneNumberValidator::class, 'when' => fn(): bool => [] !== array_filter($this->phones)],
			['relatedPhones', 'safe']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'name' => 'Имя',
			'surname' => 'Фамилия',
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
			'update_password' => 'Новый пароль',
			'partner_id' => 'Партнёр'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersTokens(): ActiveQuery
	{
		return $this->hasMany(UsersTokens::class, ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersToPhones(): ActiveQuery
	{
		return $this->hasMany(RelUsersToPhones::class, ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPhones(): ActiveQuery
	{
		return $this->hasMany(PhonesAR::class, ['id' => 'phone_id'])->via('relatedUsersToPhones');
	}

	/**
	 * @param mixed $relatedPhones
	 * @throws Throwable
	 */
	public function setRelatedPhones(mixed $relatedPhones): void
	{
		RelUsersToPhones::linkModels($this, $relatedPhones);
	}

	/**
	 * @return string[]
	 */
	public function getPhones(): array
	{
		if (null === $this->_phones) {
			$this->_phones = ArrayHelper::getColumn($this->relatedPhones, 'phone');
		}
		return $this->_phones;
	}

	/**
	 * @param mixed $phones
	 */
	public function setPhones(mixed $phones): void
	{
		$this->_phones = (array)$phones;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return "{$this->surname} {$this->name}";
	}

	/**
	 * @inheritDoc
	 */
	public function save($runValidation = true, $attributeNames = null): bool
	{
		if (true === $saved = parent::save($runValidation, $attributeNames)) {
			/*
			 * Это не очень красиво, и я предполагал сделать это через релейшен-атрибуты, проверяемые в
			 * \app\components\db\ActiveRecordTrait::createModel(mappedParams)
			 * Вышло так, пусть будет. По крайней мере, выглядит логично.
			*/
			$this->relatedPhones = Phones::add($this->_phones);
		}
		return $saved;
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPartner(): ActiveQuery
	{
		return $this->hasOne(Partners::class, ['id' => 'partner_id']);
	}
}
