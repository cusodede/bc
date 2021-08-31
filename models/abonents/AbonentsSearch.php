<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\products\Products;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class AbonentsSearch
 * @package app\models\abonents
 */
class AbonentsSearch extends Abonents
{
	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id'], 'integer'],
			[['name', 'surname', 'patronymic'], 'safe'],
		];
	}

	/**
	 * @throws InvalidConfigException
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = Abonents::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id'],
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere(['id' => $this->id])
			->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'surname', $this->surname])
			->andFilterWhere(['like', 'patronymic', $this->patronymic]);

		return $dataProvider;
	}

	/**
	 * @throws InvalidConfigException
	 * @throws NotFoundHttpException
	 */
	public function searchProductsToAbonent(array $params): array
	{
		$model = Abonents::findOne($params['id']);
		if (null === $model) {
			throw new NotFoundHttpException();
		}

		$query = Products::find()
			->where(['IN', 'id', ArrayHelper::getColumn(
				$model->relatedAbonentsToProducts, 'product_id'
			)])->active();

		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->setSort([
			'attributes' => ['created_at', 'status_id']
		]);

		return ['dataProvider' => $dataProvider, 'model' => $model];
	}
}
