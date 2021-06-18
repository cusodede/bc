<?php
declare(strict_types = 1);

namespace app\models\store;

use app\models\dealers\Dealers;
use app\models\managers\Managers;
use app\models\seller\Sellers;
use app\models\store\active_record\references\RefStoresTypes;
use app\models\store\active_record\StoresAR;
use app\models\sys\users\Users;
use pozitronik\core\models\LCQuery;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use Throwable;

/**
 * Class StoresSearch
 * @property null|string $seller
 * @property null|string $manager
 * @property null|string $dealerSearch
 * @property null|string $typeName
 */
final class StoresSearch extends StoresAR {

	public ?string $seller = null;
	public ?string $dealerSearch = null;
	public ?string $manager = null;
	public ?string $typeName = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'deleted'], 'integer'],
			[['name', 'seller', 'manager', 'dealerSearch', 'typeName'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return ArrayHelper::merge(parent::attributeLabels(), [
			'dealerSearch' => 'Дилер',
			'seller' => 'Продавец',
			'manager' => 'Менеджер',
			'typeName' => 'Тип'
		]);
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params):ActiveDataProvider {
		$query = $this->setQuery();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$this->setSort($dataProvider);
		$this->load($params);
		$query->joinWith(['sellers', 'managers', 'dealer', 'refStoresTypes']);

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
			->andFilterWhere([self::tableName().'.deleted' => $this->deleted])
			->andFilterWhere(['like', RefStoresTypes::tableName().'.name', $this->typeName])
			->andFilterWhere(['like', Sellers::tableName().'.surname', $this->seller])
			->andFilterWhere(['like', Managers::tableName().'.surname', $this->manager])
			->andFilterWhere(['like', Dealers::tableName().'.name', $this->dealerSearch]);

		$this->filterDataByUser($query);
	}

	/**
	 * Filters the records shown for current user
	 * @param $query
	 * @throws Throwable
	 */
	private function filterDataByUser($query):void {
		$user = Users::Current();
		if ($user->isAllPermissionsGranted()) {
			return;
		}
		$manager = Managers::findOne(['user' => $user->id]);
		if (null === $manager) {
			return;
		}

		if ($user->hasPermission(['dealer_stores'])) {
			$query->andFilterWhere(
				[
					'in',
					Dealers::tableName().'.id',
					ArrayHelper::getColumn($manager->relatedDealersToManagers, 'dealer_id')
				]
			);
		} elseif ($user->hasPermission(['manager_store'])) {
			$query->andFilterWhere(
				[
					'in',
					self::tableName().'.id',
					ArrayHelper::getColumn($manager->relatedManagersToStores, 'store_id')
				]
			);
		}
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'name',
				'deleted',
				'typeName' => [
					'asc' => [RefStoresTypes::tableName().'.name' => SORT_ASC],
					'desc' => [RefStoresTypes::tableName().'.name' => SORT_DESC]
				],
				'dealerSearch' => [
					'asc' => [Dealers::tableName().'.name' => SORT_ASC],
					'desc' => [Dealers::tableName().'.name' => SORT_DESC]
				]
			],
		]);
	}

	/**
	 * @return LCQuery
	 */
	private function setQuery():LCQuery {
		return self::find()
			->select(
				[
					self::tableName().'.*',
					RefStoresTypes::tableName().'.name  AS typeName',
					Dealers::tableName().'.name  AS dealerSearch'
				]
			)
			->distinct()
			->active();
	}

}