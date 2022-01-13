<?php
declare(strict_types = 1);

namespace app\modules\export\models;

use app\components\db\ActiveQuery;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class SysExportSearch
 */
final class SysExportSearch extends SysExport {
	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id'], 'filter', 'filter' => 'trim'],
			[['id'], 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return ArrayHelper::merge(parent::attributeLabels(), [
			'file' => 'Файл'
		]);
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params):ActiveDataProvider {
		$query = self::find()->distinct()->active();

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
	 * @param ActiveQuery $query
	 * @return void
	 * @throws Throwable
	 */
	private function filterData(ActiveQuery $query):void {
		$query->andFilterWhere([self::tableName().'.id' => $this->id]);

	}

	/**
	 * @param $dataProvider
	 */
	private function setSort($dataProvider):void {
		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_DESC],
			'attributes' => [
				'id'
			]
		]);
	}
}