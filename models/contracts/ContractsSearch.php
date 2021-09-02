<?php
declare(strict_types = 1);

namespace app\models\contracts;

use yii\data\ActiveDataProvider;

/**
 * Class ContractsSearch
 * @package app\models\contracts
 */
class ContractsSearch extends Contracts
{
	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['id', 'deleted'], 'integer'],
			[['contract_number', 'contract_number_nfs', 'signing_date', 'created_at', 'updated_at'], 'safe'],
		];
	}

	/**
	 * Creates data provider instance with search query applied
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = Contracts::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id'],
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'signing_date' => $this->signing_date,
		])->andFilterWhere(['like', 'contract_number', $this->contract_number])
			->andFilterWhere(['like', 'contract_number_nfs', $this->contract_number_nfs]);

		return $dataProvider;
	}
}
