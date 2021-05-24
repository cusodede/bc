<?php
declare(strict_types = 1);

namespace app\models\subscriptions;

use yii\data\ActiveDataProvider;

/**
 * Class SubscriptionsSearch
 * @package app\models\subscriptions
 */
class SubscriptionsSearch extends Subscriptions
{
	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'product_id', 'category_id', 'user_id'], 'integer'],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = Subscriptions::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['subscriptions.id' => SORT_ASC],
			'attributes' => ['subscriptions.id'],
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->joinWith(['category', 'product', 'user']);

		$query->andFilterWhere(['subscriptions.id' => $this->id])
			->andFilterWhere(['subscriptions.category_id' => $this->category_id]);

		return $dataProvider;
	}
}