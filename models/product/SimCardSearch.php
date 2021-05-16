<?php
declare(strict_types = 1);

namespace app\models\product;

use app\models\product\active_record\SimCardAR;
use yii\data\ActiveDataProvider;

/**
 * Class SimCardSearch
 * todo
 */
class SimCardSearch extends SimCardAR {
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