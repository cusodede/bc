<?php
declare(strict_types = 1);

namespace app\models\reward;

use app\models\reward\active_record\RewardsAR;
use yii\data\ActiveDataProvider;

/**
 * todo
 */
class RewardsSearch extends RewardsAR {

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