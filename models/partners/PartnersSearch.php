<?php
declare(strict_types = 1);

namespace app\models\partners;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * Модель поиска по партнерам
 * Class PartnersSearch
 * @package app\models\partners
 */
class PartnersSearch extends Partners
{
	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'category_id'], 'integer'],
			[['name', 'inn'], 'safe'],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = Partners::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id', 'name', 'inn'],
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere(['id' => $this->id])
			->andFilterWhere(['category_id' => $this->category_id])
			->andFilterWhere(['like', 'inn', $this->inn])
			->andFilterWhere(['like', 'name', $this->name]);

		return $dataProvider;
	}

	/**
	 * Статичный поиск по параметрам для GraphQl
	 * @param array $params
	 * @return array
	 * @throws Exception
	 */
	public static function searchWithParams(array $params): array
	{
		$query = Partners::find();
		//Обрабатываем комбинированный параметр $search
		if ($search = ArrayHelper::getValue($params, 'search')) {
			$query->andFilterWhere([
				'or',
				['like', 'inn', $search],
				['like', 'name', $search],
			]);
		}
		// Не забываем удалить, так как нет такого параметра в partners
		ArrayHelper::remove($params, 'search');
		$query->andFilterWhere($params);
		return $query->all();
	}
}