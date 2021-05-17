<?php
declare(strict_types = 1);

namespace app\models\partners;

use yii\data\ActiveDataProvider;

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
			[['id'], 'integer'],
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
			->andFilterWhere(['like', 'inn', $this->inn])
			->andFilterWhere(['like', 'name', $this->name]);

		return $dataProvider;
	}
}