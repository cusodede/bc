<?php
declare(strict_types = 1);

namespace app\models\products;

use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * Поисковая модель продуктов
 * Class ProductsSearch
 * @package app\models\product
 */
class ProductsSearch extends Products
{
	public ?string $category_id = null;
	public ?bool $trial = null;
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
		$sort = ArrayHelper::getValue($params, $this->formName() . '.sort');

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
		$query->joinWith(['relatedSubscription']);

		$query->andFilterWhere(['products.id' => $this->id])
			->andFilterWhere(['products.type_id' => $this->type_id])
			->andFilterWhere(['products.partner_id' => $this->partner_id])
			->andFilterWhere(['partners.category_id' => $this->category_id])
			->andFilterWhere(['like', 'products.name', $this->name]);

		if (null !== $this->trial) {
			$query->andWhere([$this->trial ? '>' : '=', 'subscriptions.trial_count', 0]);
		}

		if (null !== $this->active) {
			$query->andWhere($this->getActiveDateCondition($this->active));
		}

		return $dataProvider;
	}

	/**
	 * У нас есть дата старта и дата окончания продукта, которые могут быть как даты в формате 'Y-m-d H:i:s',
	 * так и null, если допустим продукт бессрочный. Нам надо фильтровать активные и не активные продукты,
	 * если даты null считаем продукт активным бессрочно.
	 * @param bool $isActive
	 * @return array
	 */
	public function getActiveDateCondition(bool $isActive): array
	{
		$nowDateTime = new Expression('now()');
		return $isActive ?
			[
				'or',
				['and', ['<=', 'products.start_date', $nowDateTime], ['>=', 'products.end_date', $nowDateTime]],
				['and', ['<=', 'products.start_date', $nowDateTime], ['products.end_date' => null]],
				['and', ['>=', 'products.end_date', $nowDateTime], ['products.start_date' => null]],
				['and', ['products.end_date' => null], ['products.start_date' => null]],
			] :
			['or', ['>', 'products.start_date', $nowDateTime], ['<', 'products.end_date', $nowDateTime]];
	}
}