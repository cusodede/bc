<?php
declare(strict_types = 1);

namespace app\models\reward;

use app\models\reward\active_record\references\RefRewardOperations;
use app\models\reward\active_record\references\RefRewardRules;
use app\models\reward\active_record\RewardsAR;
use app\modules\status\models\Status;
use app\modules\status\models\StatusRulesModel;
use pozitronik\core\models\LCQuery;
use yii\data\ActiveDataProvider;
use app\models\sys\users\Users;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use Throwable;

/**
 * Class RewardsSearch
 * @property null|string $userName
 * @property null|string $ruleName
 * @property null|string $currentStatus
 */
final class RewardsSearch extends RewardsAR {

	public ?string $userName = null;
	public ?string $ruleName = null;
	public ?string $currentStatus = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'value', 'deleted', 'operation', 'currentStatus'], 'integer'],
			['create_date', 'date', 'format' => 'php:Y-m-d H:i'],
			[['userName', 'ruleName'], 'string', 'max' => 255],
			[['currentStatus'], 'filter', 'filter' => static function($value) {
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
			RefRewardOperations::tableName().'.name AS operationName',
			RefRewardRules::tableName().'.name AS ruleName',
			Users::tableName().'.username AS userName',
			/*Так обеспечивается наполнение атрибута + алфавитная сортировка*/
			"ELT(".Status::tableName().'.status'.", '".implode("','", ArrayHelper::map(
				StatusRulesModel::getAllStatuses(Rewards::class),
				'id',
				'name'
			))."') AS currentStatus"
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
		$query = self::find();
		$query->joinWith(['relStatus', 'relatedUser', 'refRewardOperation', 'refRewardRule']);
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
			->andFilterWhere([self::tableName().'.value' => $this->value])
			->andFilterWhere(['>=', self::tableName().'.create_date', $this->create_date])
			->andFilterWhere([self::tableName().'.operation' => $this->operation])
			->andFilterWhere(['like', RefRewardRules::tableName().'.name', $this->ruleName])
			->andFilterWhere(['like', Users::tableName().'.username', $this->userName])
			->andFilterWhere(['like', Users::tableName().'.username', $this->userName])
			->andFilterWhere([Status::tableName().'.status' => $this->currentStatus])
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
				'value',
				'create_date',
				'deleted',
				'currentStatus' => [
					'asc' => ['currentStatus' => SORT_ASC],
					'desc' => ['currentStatus' => SORT_DESC]
				],
				'operationName' => [
					'asc' => [RefRewardOperations::tableName().'.name' => SORT_ASC],
					'desc' => [RefRewardOperations::tableName().'.name' => SORT_DESC]
				],
				'ruleName' => [
					'asc' => [RefRewardRules::tableName().'.name' => SORT_ASC],
					'desc' => [RefRewardRules::tableName().'.name' => SORT_DESC]
				],
				'userName' => [
					'asc' => [Users::tableName().'.username' => SORT_ASC],
					'desc' => [Users::tableName().'.username' => SORT_DESC]
				]
			],
		]);
	}

}