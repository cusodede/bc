<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use app\models\sys\permissions\active_record\PermissionsCollections;
use yii\data\ActiveDataProvider;

/**
 * Class PermissionsCollectionsSearch
 * @property null|string $permission
 */
class PermissionsCollectionsSearch extends PermissionsCollections {

	public ?string $permission;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name', 'permission'], 'string', 'max' => 128]
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = PermissionsCollections::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$this->setSort($dataProvider);
		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		$query->joinWith(['relatedPermissions', 'relatedUsers']);
		$this->filterData($query);

		return $dataProvider;
	}

	/**
	 * @param $query
	 * @return void
	 */
	private function filterData($query):void {
		$query->andFilterWhere(['like', self::tableName().'.name', $this->name])
			->andFilterWhere(['like', Permissions::tableName().'.name', $this->permission]);
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['name' => SORT_ASC],
			'attributes' => ['name']
		]);
	}
}