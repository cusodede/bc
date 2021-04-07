<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use yii\data\ActiveDataProvider;

/**
 * Class PermissionsSearch
 */
class PermissionsSearch extends PermissionsAR {

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = Permissions::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'username',
				'login',
				'email',
			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		return $dataProvider;
	}

}