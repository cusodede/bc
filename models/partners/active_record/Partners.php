<?php
declare(strict_types = 1);

namespace app\models\partners\active_record;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\products\active_record\Products;

/**
 * This is the model class for table "partners".
 *
 * @property int $id
 * @property string $name Название партнера
 * @property string $created_at Дата создания партнера
 * @property string $inn ИНН партнера
 * @property int $deleted Флаг активности
 * @property string $updated_at Дата обновления партнера
 *
 * @property Products[] $products
 */
class Partners extends ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'partners';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['name', 'inn'], 'required', 'message' => 'Заполните {attribute} партнера!'],
			[['created_at', 'updated_at'], 'safe'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 64, 'min' => 3],
			[['inn'], 'string', 'max' => 12],
			[['inn'], 'unique', 'message' => 'Партнер с таким {attribute} уже существует'],
			[['inn'], 'match', 'pattern' => '/^\d{10}(?:\d{2})?$/', 'message' => 'Некорректный {attribute}'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'name' => 'Наименование',
			'created_at' => 'Дата создания',
			'inn' => 'ИНН',
			'deleted' => 'Флаг удаления',
			'updated_at' => 'Дата обновления',
		];
	}

	/**
	 * Gets query for [[Products]].
	 *
	 * @return ActiveQuery
	 */
	public function getProducts(): ActiveQuery
	{
		return $this->hasMany(Products::class, ['partner_id' => 'id']);
	}
}
