<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

use app\modules\status\ActiveHintsModule;
use pozitronik\core\traits\ARExtended;
use pozitronik\helpers\DateHelper;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class ActiveStorage
 */
class ActiveStorage extends ActiveRecord implements ActiveStorageInterface {
	use ARExtended;
	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return ActiveHintsModule::getConfigParameter('table_name');
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['for', 'header', 'content', 'placement'], 'string'],
			[['for'], 'unique'],
			[['daddy'], 'default', 'value' => Yii::$app->user->id],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'for' => 'Идентификатор',
			'header' => 'Заголовок',
			'content' => 'Содержимое',
			'placement' => 'Расположение'
		];
	}

}