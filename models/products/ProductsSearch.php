<?php
declare(strict_types = 1);

namespace app\models\product;

use app\models\core\prototypes\ActiveRecordTrait;
use yii\data\ActiveDataProvider;

/**
 * Поисковая модель продуктов
 * Class ProductsSearch
 * @package app\models\product
 */
class ProductsSearch extends Products
{
	use ActiveRecordTrait;

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = static::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => []
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		// $query->andFilterWhere(['sys_users.id' => $this->id])

		return $dataProvider;
	}
}