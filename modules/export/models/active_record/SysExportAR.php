<?php
declare(strict_types = 1);

namespace app\modules\export\models\active_record;

use app\models\sys\users\Users;
use app\modules\history\behaviors\HistoryBehavior;
use app\modules\s3\models\cloud_storage\CloudStorage;
use pozitronik\helpers\DateHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\components\db\ActiveRecordTrait;

/**
 * Class SysExportAR
 * Хранит записи о экспортах
 * @property int $id ID
 * @property int $status Статус выгрузки
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 * @property string $extra_data Доп. информация
 * @property int $user Пользователь
 * @property int $storage Облачное хранилище
 *
 * @property Users $relatedUser Пользователь
 * @property CloudStorage $relatedStorage Облачное хранилище
 */
class SysExportAR extends ActiveRecord {
	use ActiveRecordTrait;

	public const STATUS_REGISTERED = 0;
	public const STATUS_EXPORTING = 1;
	public const STATUS_DONE = 2;
	public const STATUS_ERROR = 3;

	public const STATUSES = [
		self::STATUS_REGISTERED => 'Взято в работу',
		self::STATUS_EXPORTING => 'Экспортируется',
		self::STATUS_DONE => 'Готово',
		self::STATUS_ERROR => 'Ошибка'
	];

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'sys_export';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors():array {
		return [
			'history' => ['class' => HistoryBehavior::class],
			[
				'class' => TimestampBehavior::class,
				'value' => DateHelper::lcDate()
			]
		];
	}

	/**
	 * @inheritDoc
	 */
	public function rules():array {
		return [
			[['id', 'status', 'user', 'storage'], 'integer'],
			['status', 'default', 'value' => self::STATUS_REGISTERED],
			[['user'], 'default', 'value' => Yii::$app->user->id??null],
			[['created_at', 'updated_at'], 'string', 'max' => 255],
			[['extra_data'], 'string'],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'status' => 'Статус выгрузки',
			'user' => 'Пользователь',
			'created_at' => 'Дата создания',
			'updated_at' => 'Дата обновления',
			'extra_data' => 'Доп. информация',
			'storage' => 'Облачное хранилище'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUser():ActiveQuery {
		return $this->hasOne(Users::class, ['id' => 'user']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedStorage():ActiveQuery {
		return $this->hasOne(CloudStorage::class, ['id' => 'storage']);
	}
}