<?php
declare(strict_types = 1);

namespace app\modules\import\models\active_record;

use app\components\db\ActiveRecordTrait;
use app\modules\import\models\ImportModel;
use yii\db\ActiveRecord;

/**
 * Хранение статусов загрузок
 *
 * @property int $id
 * @property string $model
 * @property int $domain
 * @property string $created_at Время создания импорта
 * @property string|null $filename Имя загруженного для импорта файла
 * @property int $status Статус
 * @property int|null $processed Строк загружено для разбора
 * @property int|null $skipped Строк пропущено
 * @property int|null $imported Строк импортировано
 * @property string $error
 *
 * @property-read int $percent процент импортированных строк
 * @property-read string $statusLabel
 *
 */
class ImportStatus extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_import_status';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['status'], 'default', 'value' => ImportModel::STATUS_REGISTERED],
			[['model', 'domain', 'status'], 'required'],
			[['processed', 'skipped', 'imported'], 'default', 'value' => null],
			[['domain', 'status', 'processed', 'skipped', 'imported'], 'integer'],
			[['created_at'], 'safe'],
			[['error'], 'string'],
			[['model', 'filename'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'model' => 'Model',
			'domain' => 'Domain',
			'created_at' => 'Время создания импорта',
			'filename' => 'Имя загруженного для импорта файла',
			'status' => 'Статус',
			'processed' => 'Строк загружено для разбора',
			'skipped' => 'Строк пропущено при импорте',
			'imported' => 'Строк импортировано',
			'error' => 'Error',
		];
	}

	/**
	 * @param string $model
	 * @param int $domain
	 * @return static|null
	 */
	public static function findImportStatus(string $model, int $domain):?static {
		return static::find()->where(compact('model', 'domain'))->one();
	}

	/**
	 * @return int
	 */
	public function getPercent():int {
		return $this->imported
			?(int)((($this->imported + $this->skipped) / $this->processed) * 100)
			:0;
	}

	/**
	 * @return string
	 */
	public function getStatusLabel():string {
		return match ($this->status) {
			ImportModel::STATUS_REGISTERED => 'В очереди',
			ImportModel::STATUS_PARSING => 'Обработка файла',
			ImportModel::STATUS_PRELOADING => 'Предзагрузка данных',
			ImportModel::STATUS_IMPORTING => 'Импорт',
			ImportModel::STATUS_DONE => 'Завершено',
			ImportModel::STATUS_ERROR => 'Ошибка',
			default => 'Неизвестно'
		};
	}
}
