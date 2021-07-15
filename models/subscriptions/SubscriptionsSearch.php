<?php
declare(strict_types = 1);

namespace app\models\subscriptions;

use Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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
			[['id', 'product_id'], 'integer'],
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

		$query = Subscriptions::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		if (null !== $pagination) {
			$dataProvider->setPagination($pagination);
		}

		$dataProvider->setSort([
			'defaultOrder' 	=> ['subscriptions.id' => SORT_ASC],
			'attributes' 	=> ['subscriptions.id'],
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->joinWith(['product']);

		$query->andFilterWhere(['subscriptions.id' => $this->id]);

		return $dataProvider;
	}
}