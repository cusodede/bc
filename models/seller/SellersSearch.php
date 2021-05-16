<?php
declare(strict_types = 1);

namespace app\models\seller\seller;

use app\models\seller\seller\active_record\Sellers as ActiveRecordSellers;
use yii\data\ActiveDataProvider;

/**
 * Class StoresSearch
 * todo
 */
class SellersSearch extends ActiveRecordSellers {
	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = self::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		return $dataProvider;
	}
}