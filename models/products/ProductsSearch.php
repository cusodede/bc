<?php
declare(strict_types = 1);

namespace app\models\products;

use yii\data\ActiveDataProvider;
use yii\data\Sort;
use Exception;
use yii\helpers\ArrayHelper;

/**
 * Поисковая модель продуктов
 * Class ProductsSearch
 * @package app\models\product
 */
class ProductsSearch extends Products
{
	/**
	 * @var string|null
	 */
	public ?string $category_id = null;
	/**
	 * @var bool|null
	 */
	public ?bool $trial = null;
	/**
	 * @var bool|null
	 */
	public ?bool $active = null;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'type_id', 'partner_id', 'category_id'], 'integer'],
			[['trial', 'active'], 'boolean'],
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
		$pagination = ArrayHelper::getValue($params, $this->formName() . '.pagination');
		$sort       = ArrayHelper::getValue($params, $this->formName() . '.sort');

		$query = Products::find()->active();

		$dataProvider = new ActiveDataProvider(['query' => $query]);

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

		$query->joinWith(['relatedPartner', 'relatedSubscription']);

		$query->andFilterWhere([
			'products.id' => $this->id,
			'products.type_id' => $this->type_id,
			'products.partner_id' => $this->partner_id,
			'partners.category_id' => $this->category_id
		]);
		$query->andFilterWhere(['like', 'products.name', $this->name]);

		if (null !== $this->trial) {
			$query->andWhere([$this->trial ? '>' : '=', 'subscriptions.trial_count', 0]);
		}

		if (null !== $this->active) {
			$query->whereActivePeriod($this->active);
		}

		return $dataProvider;
	}
}