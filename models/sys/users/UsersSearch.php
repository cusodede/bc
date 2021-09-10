<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use app\models\sys\users\active_record\Users as ActiveRecordUsers;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class UsersSearch
 */
class UsersSearch extends ActiveRecordUsers
{
	public ?int $limit = null;
	public ?int $offset = null;
	public ?string $search = null;
	public ?string $username = null;

	/**
	 * @inheritdoc
	 */
	public function rules(): array
	{
		return [
			[['id', 'limit', 'offset', 'partner_id'], 'integer'],
			[['login', 'email', 'username'], 'safe'],
			[['search'], 'string', 'min' => 3],
		];
	}

	/**
	 * @param array $params
	 * @param int[] $allowedGroups
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function search(array $params, array $allowedGroups = []): ActiveDataProvider
	{
		$query = Users::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$pagination = ArrayHelper::getValue($params, $this->formName() . '.pagination');
		if (null !== $pagination) {
			$dataProvider->setPagination($pagination);
		}

		$dataProvider->setSort([
			'defaultOrder' 	=> ['id' => SORT_ASC],
			'attributes' 	=> ['id', 'login', 'email',]
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->distinct();

		$query->andFilterWhere(['sys_users.id' => $this->id])
			->andFilterWhere(['group_id' => $allowedGroups])
			->andFilterWhere(['partner_id' => $this->partner_id])
			->andFilterWhere(['like', 'login', $this->login])
			->andFilterWhere(['or', ['like', 'name', $this->username], ['like', 'surname', $this->username]])
			->andFilterWhere(['like', 'email', $this->email]);

		// Поиск из GraphQL
		if (null !== $this->search) {
			$query->andFilterWhere([
				'or',
				['like', 'name', $this->search],
				['like', 'surname', $this->search],
				['like', 'email', $this->search],
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
}
