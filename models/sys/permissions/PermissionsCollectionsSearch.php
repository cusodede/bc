<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use app\models\sys\permissions\active_record\Permissions;
use app\models\sys\permissions\active_record\PermissionsCollections;
use yii\data\ActiveDataProvider;

/**
 * Class PermissionsCollectionsSearch
 */
class PermissionsCollectionsSearch extends Permissions {

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = PermissionsCollections::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id'
			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		return $dataProvider;
	}

}