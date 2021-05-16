<?php
declare(strict_types = 1);

namespace app\models\merch;

use app\models\merch\active_record\MerchAR;
use yii\data\ActiveDataProvider;

/**
 * Class MerchSearch
 * todo
 */
class MerchSearch extends MerchAR {

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