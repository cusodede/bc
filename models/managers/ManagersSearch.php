<?php
declare(strict_types = 1);

namespace app\models\managers;

use app\models\store\Stores;
use app\models\sys\users\Users;
use pozitronik\core\models\LCQuery;
use yii\data\ActiveDataProvider;
use Throwable;

/**
 * Class ManagersSearch
 * @property null|string $userId
 * @property null|string $userLogin
 * @property null|string $userEmail
 * @property null|string $store
 */
final class ManagersSearch extends Managers {

	public ?string $userId = null;
	public ?string $userEmail = null;
	public ?string $userLogin = null;
	public ?string $store = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[
				[
					'id', 'name', 'surname', 'patronymic', 'create_date', 'update_date', 'userEmail', 'userLogin',
					'userId'
				],
				'filter',
				'filter' => 'trim'
			],
			[['id', 'userId'], 'integer'],
			[['name', 'surname', 'patronymic'], 'string', 'max' => 128],
			[['deleted'], 'boolean'],
			[['userEmail', 'store'], 'string', 'max' => 255],
			['userLogin', 'string', 'max' => 64],
			['userEmail', 'email'],
			[['create_date', 'update_date'], 'date', 'format' => 'php:Y-m-d H:i']
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params):ActiveDataProvider {
		$query = self::find()->distinct()->active();
		$query->joinWith(['stores', 'dealers', 'relatedUser']);
		$this->initQuery($query);

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
	 * @throws Throwable
	 */
	private function filterData($query):void {
		$query->andFilterWhere([self::tableName().'.id' => $this->id])
			->andFilterWhere(['like', self::tableName().'.name', $this->name])
			->andFilterWhere(['like', self::tableName().'.surname', $this->surname])
			->andFilterWhere(['like', self::tableName().'.patronymic', $this->patronymic])
			->andFilterWhere(['>=', self::tableName().'.create_date', $this->create_date])
			->andFilterWhere(['>=', self::tableName().'.update_date', $this->update_date])
			->andFilterWhere([self::tableName().'.deleted' => $this->deleted])
			->andFilterWhere([Users::tableName().'.id' => $this->userId])
			->andFilterWhere([Users::tableName().'.email' => $this->userEmail])
			->andFilterWhere([Users::tableName().'.login' => $this->userLogin])
			->andFilterWhere(['like', Stores::tableName().'.name', $this->store]);
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'create_date',
				'update_date',
				'name',
				'surname',
				'patronymic',
				'deleted',
				'userId' => [
					'asc' => [Users::tableName().'.id' => SORT_ASC],
					'desc' => [Users::tableName().'.id' => SORT_DESC]
				],
				'userLogin' => [
					'asc' => [Users::tableName().'.login' => SORT_ASC],
					'desc' => [Users::tableName().'.login' => SORT_DESC]
				],
				'userEmail' => [
					'asc' => [Users::tableName().'.email' => SORT_ASC],
					'desc' => [Users::tableName().'.email' => SORT_DESC]
				]
			]
		]);
	}

	/**
	 * @param LCQuery $query
	 * @throws Throwable
	 */
	private function initQuery(LCQuery $query):void {
		$query->select([
			self::tableName().'.*',
			Users::tableName().'.id AS userId',
			Users::tableName().'.login AS userLogin',
			Users::tableName().'.email AS userEmail'
		])
			->distinct()
			->active();
	}
}