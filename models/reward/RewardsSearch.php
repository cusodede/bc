<?php
declare(strict_types = 1);

namespace app\models\reward;

use app\models\reward\active_record\references\RefRewardsRules;
use app\models\reward\active_record\RewardsAR;
use app\models\reward\config\RewardsOperationsConfig;
use app\modules\status\models\Status;
use app\modules\status\models\StatusRulesModel;
use pozitronik\core\models\LCQuery;
use pozitronik\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\sys\users\Users;
use yii\db\ActiveQuery;
use Throwable;

/**
 * Class RewardsSearch
 * @property null|string $userName
 * @property null|string $ruleName
 * @property null|string $currentStatusFilter
 */
final class RewardsSearch extends RewardsAR {

	public ?string $userName = null;
	public ?string $ruleName = null;
	public ?string $currentStatusFilter = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'quantity', 'deleted', 'operation', 'currentStatusFilter'], 'integer'],
			['create_date', 'date', 'format' => 'php:Y-m-d H:i'],
			[['userName', 'ruleName'], 'string', 'max' => 255],
			[['currentStatusFilter'], 'filter', 'filter' => static function($value) {
				return ('' === $value || null === $value)?null:(int)$value;
			}],
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelStatus():ActiveQuery {
		return $this->hasOne(Status::class, [
			'model_key' => 'id'
		])->andOnCondition(['model_name' => Rewards::class]);
	}

	/**
	 * @param LCQuery $query
	 * @throws Throwable
	 */
	private function initQuery(LCQuery $query):void {
		$query->select([
			self::tableName().'.*',
//			RefRewardsOperations::tableName().'.name AS operationName',
			RefRewardsRules::tableName().'.name AS ruleName',
			Users::tableName().'.username AS userName',
			/*Так обеспечивается наполнение атрибута + алфавитная сортировка*/
			"ELT(".Status::tableName().'.status'.", '".implode("','", ArrayHelper::map(StatusRulesModel::getAllStatuses(Rewards::class), 'id', 'name'))."') AS currentStatusFilter",
			"ELT(".self::tableName().'.operation'.", '".implode("','", RewardsOperationsConfig::mapData())."') AS operationName"//todo: сделать для таких полей отдельный метод
		])
			->distinct()
			->active();
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params):ActiveDataProvider {
		$query = Rewards::find();
		$query->joinWith(['relStatus', 'relatedUser', 'refRewardsRules']);
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
	 */
	private function filterData($query):void {
		$query->andFilterWhere([self::tableName().'.id' => $this->id])
			->andFilterWhere([self::tableName().'.quantity' => $this->quantity])
			->andFilterWhere(['>=', self::tableName().'.create_date', $this->create_date])
			->andFilterWhere([self::tableName().'.operation' => $this->operation])
			->andFilterWhere(['like', RefRewardsRules::tableName().'.name', $this->ruleName])
			->andFilterWhere(['like', Users::tableName().'.username', $this->userName])
			->andFilterWhere(['like', Users::tableName().'.username', $this->userName])
			->andFilterWhere([Status::tableName().'.status' => $this->currentStatusFilter])
			->andFilterWhere([self::tableName().'.deleted' => $this->deleted]);
	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'quantity',
				'create_date',
				'deleted',
				'currentStatusFilter' => [
					'asc' => ['currentStatusFilter' => SORT_ASC],
					'desc' => ['currentStatusFilter' => SORT_DESC]
				],
				'operationName' => [
					'asc' => ['operationName' => SORT_ASC],
					'desc' => ['operationName' => SORT_DESC]
				],
				'ruleName' => [
					'asc' => [RefRewardsRules::tableName().'.name' => SORT_ASC],
					'desc' => [RefRewardsRules::tableName().'.name' => SORT_DESC]
				],
				'userName' => [
					'asc' => [Users::tableName().'.username' => SORT_ASC],
					'desc' => [Users::tableName().'.username' => SORT_DESC]
				]
			],
		]);
	}

}