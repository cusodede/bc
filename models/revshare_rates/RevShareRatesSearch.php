<?php
declare(strict_types = 1);

namespace app\models\revshare_rates;

use yii\data\ActiveDataProvider;

/**
 * Class RevShareRatesSearch
 * @package app\models\revshare_rates
 */
class RevShareRatesSearch extends RevShareRates
{
	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['id', 'product_id', 'condition_value', 'deleted'], 'integer'],
			[['type', 'rate', 'created_at', 'updated_at'], 'safe'],
		];
	}

	/**
	 * Creates data provider instance with search query applied
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = RevShareRates::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id'              => $this->id,
			'product_id'      => $this->product_id,
			'type'            => $this->type,
			'rate'            => $this->rate,
			'condition_value' => $this->condition_value,
			'deleted'         => $this->deleted,
			'created_at'      => $this->created_at,
			'updated_at'      => $this->updated_at,
		]);

		return $dataProvider;
	}
}
