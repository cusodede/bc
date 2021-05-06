<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use yii\data\ActiveDataProvider;

/**
 * Class PermissionsSearch
 */
final class PermissionsSearch extends Permissions {

	/**
	 * @inheritDoc
	 */
	public function rules():array {
		return [
			['id', 'integer'],
			['priority', 'integer', 'min' => 0, 'max' => 100],
			[['name', 'controller', 'action', 'verb'], 'string', 'max' => 255]
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = Permissions::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$this->setSort($dataProvider);
		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		$this->filterData($query);

		return $dataProvider;
	}

	/**
	 * @param $query
	 * @return void
	 */
	private function filterData($query):void {
		$query->andFilterWhere([self::tableName().'.id' => $this->id])
			->andFilterWhere([self::tableName().'.priority' => $this->priority])
			->andFilterWhere(['like', self::tableName().'.name', $this->name])
			->andFilterWhere(['like', self::tableName().'.controller', $this->controller])
			->andFilterWhere(['like', self::tableName().'.action', $this->action])
			->andFilterWhere([self::tableName().'.verb' => $this->verb]);
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id', 'name', 'controller', 'action', 'verb', 'priority']
		]);
	}

}