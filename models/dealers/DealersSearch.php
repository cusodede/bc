<?php
declare(strict_types = 1);

namespace app\models\dealers;

use app\models\dealers\active_record\DealersAR;
use app\models\dealers\active_record\relations\RelDealersToStores;
use app\models\managers\Managers;
use app\models\store\Stores;
use app\models\sys\users\Users;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use Throwable;

/**
 * Class StoresSearch
 * @property null|string $store
 */
final class DealersSearch extends DealersAR {

	public ?string $store = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'deleted'], 'integer'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = self::find()->distinct()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$this->setSort($dataProvider);
		$this->load($params);
		$query->joinWith(['relatedDealersToStores']);

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
			->andFilterWhere(['like', self::tableName().'.name', $this->name])
			->andFilterWhere([self::tableName().'.deleted' => $this->deleted])
			->andFilterWhere(['like', Stores::tableName().'.name', $this->store]);

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

		if ($user->hasPermission(['dealer_managers'])) {
			$query->andFilterWhere(
				[
					'in',
					RelDealersToStores::tableName().'.dealer_id',
					ArrayHelper::getColumn($manager->relatedDealersToManagers, 'dealer_id')
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
			'attributes' => ['id', 'name', 'deleted']
		]);
	}
}