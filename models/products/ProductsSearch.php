<?php
declare(strict_types = 1);

namespace app\models\products;

use yii\data\ActiveDataProvider;

/**
 * Поисковая модель продуктов
 * Class ProductsSearch
 * @package app\models\product
 */
class ProductsSearch extends Products
{
	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'type_id', 'partner_id'], 'integer'],
			[['name'], 'safe'],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = Products::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['products.id' => SORT_ASC],
			'attributes' => ['products.id', 'products.name'],
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere(['products.id' => $this->id])
			->andFilterWhere(['products.type_id' => $this->type_id])
			->andFilterWhere(['products.partner_id' => $this->partner_id])
			->andFilterWhere(['like', 'products.name', $this->name]);

		return $dataProvider;
	}
}