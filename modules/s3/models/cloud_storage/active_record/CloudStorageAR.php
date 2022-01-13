<?php
declare(strict_types = 1);

namespace app\modules\s3\models\cloud_storage\active_record;

use app\components\db\ActiveRecordTrait;
use app\modules\history\behaviors\HistoryBehavior;
use pozitronik\helpers\DateHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_cloud_storage".
 *
 * @property int $id
 * @property string $bucket Корзина в облаке
 * @property string $key Ключ файла в облаке
 * @property string $filename Название файла
 * @property bool $uploaded Загружено
 * @property bool $deleted Удалено
 * @property bool $created_at Дата создания
 */
class CloudStorageAR extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_cloud_storage';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['bucket', 'key', 'filename'], 'required'],
			[['uploaded', 'deleted'], 'boolean'],
			['created_at', 'safe'],
			[['bucket', 'key', 'filename'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'key' => 'Ключ файла в облаке',
			'bucket' => 'Корзина в облаке',
			'filename' => 'Название файла',
			'uploaded' => 'Загружено',
			'deleted' => 'Удалено',
			'created_at' => 'Дата создания',
			'file' => 'Файл'
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors():array {
		return [
			'history' => [
				'class' => HistoryBehavior::class
			],
			[
				'class' => TimestampBehavior::class,
				'updatedAtAttribute' => false,
				'value' => DateHelper::lcDate(),
			]
		];
	}
}
