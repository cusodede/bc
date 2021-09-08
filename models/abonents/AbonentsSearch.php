<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\components\helpers\ArrayHelper;
use Throwable;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
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
	 * @param array $params
	 * @return array
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public function searchProducts(array $params): array
	{
		$id = ArrayHelper::getValue($params, 'id');
		if (null === $id || null === $model = Abonents::findOne($id)) {
			throw new NotFoundHttpException();
		}

		$query = $model->getRelatedProducts();

		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->setSort([
			'attributes' => ['created_at', 'status_id']
		]);

		return compact('dataProvider', 'model');
	}
}
