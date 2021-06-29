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
	public ?string $searchProductId = null;
	public ?string $searchAbonentPhone = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['id'], 'string'],
			[['status_id', 'searchProductId', 'searchAbonentPhone'], 'integer']
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
			'attributes'   => ['created_at', 'status_id', 'try_date']
		]);

		$this->load($params);
		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'b.id'        => $this->id,
			'ra.phone'    => $this->searchAbonentPhone,
			'rp.id'       => $this->searchProductId,
			'b.status_id' => $this->status_id
		]);

		return $dataProvider;
	}
}