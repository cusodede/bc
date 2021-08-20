<?php
declare(strict_types = 1);

namespace app\models\partners;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * Модель поиска по партнерам
 * Class PartnersSearch
 * @package app\models\partners
 */
class PartnersSearch extends Partners
{
	public ?int $limit = null;
	public ?int $offset = null;
	public ?string $search = null;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			[['id', 'category_id', 'limit', 'offset'], 'integer'],
			[['name', 'inn'], 'safe'],
			[['search'], 'string'],
		];
	}

	/**
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Exception
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = Partners::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$pagination = ArrayHelper::getValue($params, $this->formName() . '.pagination');
		if (null !== $pagination) {
			$dataProvider->setPagination($pagination);
		}

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => ['id', 'name', 'inn'],
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere(['id' => $this->id])
			->andFilterWhere(['category_id' => $this->category_id])
			->andFilterWhere(['like', 'inn', $this->inn])
			->andFilterWhere(['like', 'name', $this->name]);

		if (null !== $this->search) {
			$query->andFilterWhere([
				'or',
				['like', 'inn', $this->search],
				['like', 'name', $this->search],
			]);
		}

		if (null !== $this->limit) {
			$query->limit = $this->limit;
		}

		if (null !== $this->offset) {
			$query->offset = $this->offset;
		}

		return $dataProvider;
	}

	/**
	 * Статичный поиск по параметрам для GraphQl
	 * @param array $params
	 * @return array
	 * @throws Exception
	 */
	public static function searchWithParams(array $params): array
	{
		$query = Partners::find();
		//Обрабатываем комбинированный параметр $search
		if ($search = ArrayHelper::getValue($params, 'search')) {
			$query->andFilterWhere([
				'or',
				['like', 'inn', $search],
				['like', 'name', $search],
			]);
		}
		// Не забываем удалить, так как нет такого параметра в partners
		ArrayHelper::remove($params, 'search');
		$query->andFilterWhere($params);
		return $query->active()->all();
	}
}