<?php
declare(strict_types = 1);

namespace app\models\store;

use app\models\store\active_record\Stores as ActiveRecordStores;
use yii\data\ActiveDataProvider;

/**
 * Class StoresSearch
 * todo
 */
class StoresSearch extends ActiveRecordStores {
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