<?php
declare(strict_types = 1);

namespace app\modules\active_hints\models;

use yii\data\ActiveDataProvider;

/**
 * Class ActiveStorageSearch
 */
class ActiveStorageSearch extends ActiveStorage {
	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = ActiveStorage::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
//			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'class',
				'attribute',
				'user',
				'header',
				'content',
				'placement'

			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		return $dataProvider;
	}
}