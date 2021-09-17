<?php
declare(strict_types = 1);

namespace app\models\products;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * Class ProductsJournalSearch
 * @package app\models\products
 */
class ProductsJournalSearch extends ProductsJournal
{
	/**
	 * @var string|null идентификатор продукта для поиска.
	 */
	public ?string $searchProductId = null;
	/**
	 * @var string|null идентификатор продукта для поиска.
	 */
	public ?string $searchProductTypeId = null;
	/**
	 * @var string|null телефон абонента для поиска.
	 */
	public ?string $searchAbonentPhone = null;
	/**
	 * @var int|null идентификатор партнёра.
	 */
	public ?int $partnerId = null;
	/**
	 * @var array|null идентификаторы статусов.
	 */
	public ?array $statusIds = null;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'searchAbonentPhone'], 'string'],
			[['status_id', 'searchProductId', 'searchProductTypeId', 'partnerId'], 'integer'],
			[['statusIds'], 'safe']
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws InvalidConfigException
	 */
	public function search(array $params): ActiveDataProvider
	{
		$pagination = ArrayHelper::getValue($params, $this->formName() . '.pagination');

		$query = ProductsJournal::find()->alias('pj')->joinWith(['relatedAbonent ra', 'relatedProduct rp']);

		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if (null !== $pagination) {
			$dataProvider->setPagination($pagination);
		}

		$dataProvider->setSort([
			'defaultOrder' => ['created_at' => SORT_DESC],
			'attributes' => ['id', 'created_at']
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'pj.id' => $this->id,
			'pj.status_id' => $this->statusIds ?? $this->status_id,
			'ra.phone' => $this->searchAbonentPhone,
			'rp.id' => $this->searchProductId,
			'rp.type_id' => $this->searchProductTypeId,
			'rp.partner_id' => $this->partnerId
		]);

		return $dataProvider;
	}
}