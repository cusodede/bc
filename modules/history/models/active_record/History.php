<?php
declare(strict_types = 1);

namespace app\modules\history\models\active_record;

use app\modules\history\HistoryModule;
use pozitronik\core\helpers\ModuleHelper;
use pozitronik\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property-read string $at CURRENT_TIMESTAMP
 * @property int|null $user id пользователя, совершившего изменение
 * @property string|null $model_class Класс (FQN, либо алиас, сопоставленный в конфиге)
 * @property int|null $model_key Первичный ключ модели, если есть. Составные ключи не поддерживаются.
 * @property string|null $old_attributes Old serialized attributes
 * @property string|null $new_attributes New serialized attributes
 * @property string|null $relation_model Опциональная связанная модель (используется при построении представления истории)
 * @property string|null $scenario Опциональный сценарий события
 * @property string|null $event Событие, вызвавшее сохранение слепка истории
 * @property string|null $operation_identifier Уникальный идентификатор (обычно клиентский csrf), связывающий несколько последовательных изменений, происходящих в одном событии
 * @property string|null $delegate Опционально: идентификатор "перекрывающего" пользователя, если поддерживается приложением
 */
class History extends ActiveRecord {
	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return ArrayHelper::getValue(ModuleHelper::params(HistoryModule::class), 'tableName', 'sys_history');
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user', 'model_key'], 'integer'],
			[['old_attributes', 'new_attributes'], 'string'],
			[['model_class', 'relation_model', 'scenario', 'event', 'operation_identifier', 'delegate'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'at' => 'Время события',
			'user' => 'Пользователь',
			'model_class' => 'Модель',
			'relation_model' => 'Связанная модель',
			'old_attributes' => 'Прежние данные',
			'new_attributes' => 'Изменения',
			'eventType' => 'Тип сообытия',
			'scenario' => 'Сценарий',
			'event' => 'Событие',
			'delegate' => 'Делегировавший пользователь',
			'operation_identifier' => 'Идентификатор операции'
		];
	}
}
