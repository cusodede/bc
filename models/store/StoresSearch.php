<?php
declare(strict_types = 1);

namespace app\models\store;

use app\models\seller\Sellers;
use app\models\store\active_record\references\RefStoreTypes;
use app\models\store\active_record\StoresAR;
use yii\data\ActiveDataProvider;

/**
 * Class StoresSearch
 * @property null|string $seller
 * @property null|string $typeName
 */
class StoresSearch extends StoresAR {

	public ?string $seller = null;
	public ?string $typeName = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			['id', 'integer'],
			[['name', 'seller', 'typeName'], 'string', 'max' => 255],
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

		if (!$this->validate()) return $dataProvider;

		$query->joinWith(['sellers', 'refStoreType']);
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
			->andFilterWhere(['like', RefStoreTypes::tableName().'.name', $this->typeName])
			->andFilterWhere(['like', Sellers::tableName().'.name', $this->seller]);
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id', 'name']
		]);
	}

}