<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use yii\data\ActiveDataProvider;
use app\models\sys\users\active_record\Users;
use app\models\sys\permissions\active_record\PermissionsCollections;

/**
 * Class PermissionsSearch
 * @property null|string $user
 * @property null|string $collection
 */
final class PermissionsSearch extends Permissions
{

	public ?string $user = null;
	public ?string $collection = null;

	/**
	 * @inheritDoc
	 */
	public function rules(): array
	{
		return [
			['id', 'integer'],
			['priority', 'integer', 'min' => 0, 'max' => 100],
			[['name', 'controller', 'action', 'verb', 'user', 'collection'], 'string', 'max' => 255]
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = Permissions::find()->distinct()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$this->setSort($dataProvider);
		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		$query->joinWith(['relatedUsers', 'relatedPermissionsCollections']);
		$this->filterData($query);

		return $dataProvider;
	}

	/**
	 * @param $query
	 * @return void
	 */
	private function filterData($query): void
	{
		$query->andFilterWhere([self::tableName() . '.id' => $this->id])
			->andFilterWhere([self::tableName() . '.priority' => $this->priority])
			->andFilterWhere(['like', self::tableName() . '.name', $this->name])
			->andFilterWhere(['like', self::tableName() . '.controller', $this->controller])
			->andFilterWhere(['like', self::tableName() . '.action', $this->action])
			->andFilterWhere([self::tableName() . '.verb' => $this->verb])
			->andFilterWhere(['like', Users::tableName() . '.username', $this->user])
			->andFilterWhere(['like', PermissionsCollections::tableName() . '.name', $this->collection]);
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider): void
	{
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id', 'name', 'controller', 'action', 'verb', 'priority']
		]);
	}

}