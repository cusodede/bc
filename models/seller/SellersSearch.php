<?php
declare(strict_types = 1);

namespace app\models\seller;

use app\models\seller\active_record\SellersAR;
use app\models\store\Stores;
use yii\data\ActiveDataProvider;

/**
 * Class StoresSearch
 * @property null|string $store
 */
final class SellersSearch extends SellersAR {

	public ?string $store = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'deleted'], 'integer'],
			[['name', 'store'], 'string', 'max' => 255],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = self::find()->distinct()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$this->setSort($dataProvider);
		$this->load($params);
		$query->joinWith(['stores']);

		if (!$this->validate()) return $dataProvider;

		$this->filterData($query);

		return $dataProvider;
	}

	/**
	 * @param $query
	 * @return void
	 */
	private function filterData($query):void {
		$query->andFilterWhere([self::tableName().'.id' => $this->id])
			->andFilterWhere(['like', self::tableName().'.name', $this->name])
			->andFilterWhere([self::tableName().'.deleted' => $this->deleted])
			->andFilterWhere(['like', Stores::tableName().'.name', $this->store]);
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id', 'name', 'deleted']
		]);
	}
}