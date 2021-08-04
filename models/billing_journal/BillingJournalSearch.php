<?php
declare(strict_types = 1);

namespace app\models\billing_journal;

use yii\data\ActiveDataProvider;

/**
 * Class BillingJournalSearch
 * @package app\models\billing_journal
 */
class BillingJournalSearch extends BillingJournal
{
	/**
	 * @var string|null идентификатор продукта для поиска.
	 */
	public ?string $searchProductId = null;
	/**
	 * @var string|null телефон абонента для поиска.
	 */
	public ?string $searchAbonentPhone = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['id', 'searchAbonentPhone'], 'string'],
			[['status_id', 'searchProductId'], 'integer']
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = BillingJournal::find()->alias('b')->joinWith(['relatedAbonent ra', 'relatedProduct rp']);

		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->setSort([
			'defaultOrder' => ['created_at' => SORT_DESC],
			'attributes'   => ['created_at', 'status_id']
		]);

		$this->load($params);
		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'b.id'        => $this->id,
			'b.status_id' => $this->status_id,
			'ra.phone'    => $this->searchAbonentPhone,
			'rp.id'       => $this->searchProductId,
		]);

		return $dataProvider;
	}
}