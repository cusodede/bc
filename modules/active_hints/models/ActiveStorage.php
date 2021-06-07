<?php
declare(strict_types = 1);

namespace app\modules\active_hints\models;

use app\models\core\prototypes\ActiveRecordTrait;
use app\modules\active_hints\ActiveHintsModule;
use pozitronik\helpers\DateHelper;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class ActiveStorage
 * @property int $id
 * @property string $model
 * @property string $attribute
 * @property null|string $content
 * @property null|string $header
 * @property null|int $placement
 * @property null|int $user
 * @property string $at
 */
class ActiveStorage extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return ActiveHintsModule::getConfigParameter('tableName');
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id', 'placement'], 'integer'],
			[['model', 'attribute', 'content', 'header'], 'string'],
			[['model', 'attribute'], 'unique', 'targetAttribute' => ['model', 'attribute']],
			[['user'], 'default', 'value' => Yii::$app->user->id],
			[['at'], 'default', 'value' => DateHelper::lcDate()],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'model' => 'Модель',
			'attribute' => 'Атрибут',
			'header' => 'Заголовок',
			'content' => 'Содержимое',
			'placement' => 'Расположение'
		];
	}

	/**
	 * @param Model|string $model
	 * @param string $attribute
	 * @return static|null
	 */
	public static function findActiveAttribute($model, string $attribute):?self {
		if (is_object($model)) $model = get_class($model);
		return self::find()->where(compact('model', 'attribute'))->one();
	}

}