<?php
declare(strict_types = 1);

namespace app\models\seller;

use app\models\seller\active_record\SellersAR;
use app\models\store\Stores;
use yii\data\ActiveDataProvider;

/**
 * Class StoresSearch
 * @property null|string $store
 *
 * @property null|string $passportExplodedSeries
 * @property null|string $passportExplodedNumber
 */
final class SellersSearch extends SellersAR {

	public ?string $store = null;
	public ?string $passport = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[
				[
					'id', 'deleted', 'is_wireman_shpd', 'is_resident', 'gender', 'name', 'surname', 'patronymic', 'email',
					'passport', 'inn', 'snils', 'login', 'keyword', 'birthday', 'entry_date', 'store'
				],
				'filter',
				'filter' => 'trim'
			],
			[['id', 'gender'], 'integer'],
			[['deleted', 'is_wireman_shpd', 'is_resident'], 'boolean'],
			['store', 'string', 'max' => 255],
			[['birthday', 'entry_date'], 'date', 'format' => 'php:Y-m-d'],
			[['login', 'keyword'], 'string', 'max' => 64],
			[['name', 'surname', 'patronymic', 'email', 'passport'], 'string', 'max' => 128],
			['inn', 'string', 'max' => 12],
			['snils', 'string', 'max' => 14]
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
		$query->joinWith(['stores']);

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
			->andFilterWhere(['like', self::tableName().'.surname', $this->surname])
			->andFilterWhere(['like', self::tableName().'.patronymic', $this->patronymic])
			->andFilterWhere([self::tableName().'.gender' => $this->gender])
			->andFilterWhere([self::tableName().'.birthday' => $this->birthday])
			->andFilterWhere([self::tableName().'.login' => $this->login])
			->andFilterWhere([self::tableName().'.email' => $this->email])
			->andFilterWhere(['>=', self::tableName().'.create_date', $this->create_date])
			->andFilterWhere(['>=', self::tableName().'.update_date', $this->update_date])
			->andFilterWhere([self::tableName().'.passport_series' => $this->passportExplodedSeries])
			->andFilterWhere([self::tableName().'.passport_number' => $this->passportExplodedNumber])
			->andFilterWhere([self::tableName().'.entry_date' => $this->entry_date])
			->andFilterWhere([self::tableName().'.inn' => $this->inn])
			->andFilterWhere([self::tableName().'.snils' => $this->snils])
			->andFilterWhere([self::tableName().'.keyword' => $this->keyword])
			->andFilterWhere([self::tableName().'.is_resident' => $this->is_resident])
			->andFilterWhere([self::tableName().'.is_wireman_shpd' => $this->is_wireman_shpd])
			->andFilterWhere([self::tableName().'.deleted' => $this->deleted])
			->andFilterWhere(['like', Stores::tableName().'.name', $this->store]);
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

	/**
	 * Searching passport field consists of series and number. This getter returns us series.
	 * @return false|string[]
	 */
	public function getPassportExplodedSeries() {
		$passportArray = explode(' ', $this->passport);
		return array_shift($passportArray);
	}

	/**
	 * Searching passport field consists of series and number. This getter returns us number.
	 * @return mixed|string|null
	 */
	public function getPassportExplodedNumber() {
		$passportArray = explode(' ', $this->passport);
		return array_pop($passportArray);
	}
}