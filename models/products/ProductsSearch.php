<?php
declare(strict_types = 1);

namespace app\models\products;

use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\helpers\ArrayHelper;
use Exception;
use ReflectionClass;

/**
 * Поисковая модель продуктов
 * Class ProductsSearch
 * @package app\models\product
 */
class ProductsSearch extends Products
{
	public ?string $category_id = null;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'type_id', 'partner_id', 'category_id'], 'integer'],
			[['name'], 'safe'],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Exception
	 */
	public function search(array $params): ActiveDataProvider
	{
		// Сортировка и навигация для GraphQL
		$shortName = (new ReflectionClass($this))->getShortName();
		$pagination = ArrayHelper::getValue($params, $shortName . '.pagination');
		$sort = ArrayHelper::getValue($params, $shortName . '.sort');

		$query = Products::find()->active();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (null !== $pagination) {
			$dataProvider->setPagination($pagination);
		}

		if (null !== $sort) {
			$dataProvider->setSort(new Sort(['params' => compact('sort')]));
		} else {
			$dataProvider->setSort([
				'defaultOrder' => ['id' => SORT_ASC],
				'attributes' => ['id', 'name'],
			]);
		}

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->joinWith(['relatedPartner']);

		$query->andFilterWhere(['products.id' => $this->id])
			->andFilterWhere(['products.type_id' => $this->type_id])
			->andFilterWhere(['products.partner_id' => $this->partner_id])
			->andFilterWhere(['partners.category_id' => $this->category_id])
			->andFilterWhere(['like', 'products.name', $this->name]);

		return $dataProvider;
	}
}