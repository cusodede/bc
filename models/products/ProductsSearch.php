<?php
declare(strict_types = 1);

namespace app\models\products;

use app\components\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use Throwable;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

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
	public ?int $limit = null;
	public ?int $offset = null;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'type_id', 'partner_id', 'category_id', 'limit', 'offset'], 'integer'],
			[['trial', 'active'], 'boolean'],
			[['name'], 'safe'],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Throwable
	 * @throws InvalidConfigException
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

		if (null !== $this->limit) {
			$query->limit = $this->limit;
		}

		if (null !== $this->offset) {
			$query->offset = $this->offset;
		}

		return $dataProvider;
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public function searchAbonents(array $params): ActiveDataProvider
	{
		$id = ArrayHelper::getValue($params, 'id');
		if (null === $id || null === $model = Products::findOne($id)) {
			throw new NotFoundHttpException();
		}

		$query = $model->getRelatedAbonents();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 5,
			],
		]);

		$pagination = ArrayHelper::getValue($params, $this->formName() . '.pagination');
		if (null !== $pagination) {
			$dataProvider->setPagination($pagination);
		}

		$dataProvider->setSort([
			'attributes' => ['created_at', 'status_id']
		]);

		return $dataProvider;
	}
}
