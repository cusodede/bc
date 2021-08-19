<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use app\models\sys\users\active_record\Users as ActiveRecordUsers;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * Class UsersSearch
 */
class UsersSearch extends ActiveRecordUsers
{
	public ?int $limit = null;
	public ?int $offset = null;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id', 'limit', 'offset'], 'integer'],
			[['username', 'login', 'email'], 'safe'],
		];
	}

	/**
	 * @param array $params
	 * @param int[] $allowedGroups
	 * @return ActiveDataProvider
	 * @throws Throwable
	 * @throws ForbiddenHttpException
	 */
	public function search(array $params, array $allowedGroups = []): ActiveDataProvider
	{
		$query = Users::find()->active()->scope();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$pagination = ArrayHelper::getValue($params, $this->formName() . '.pagination');
		if (null !== $pagination) {
			$dataProvider->setPagination($pagination);
		}

		$dataProvider->setSort([
			'defaultOrder' 	=> ['id' => SORT_ASC],
			'attributes' 	=> ['id', 'username', 'login', 'email',]
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->distinct();

		$query->andFilterWhere(['sys_users.id' => $this->id])
			->andFilterWhere(['group_id' => $allowedGroups])
			->andFilterWhere(['like', 'sys_users.username', $this->username])
			->andFilterWhere(['like', 'login', $this->login])
			->andFilterWhere(['like', 'email', $this->email]);

		if (null !== $this->limit) {
			$query->limit = $this->limit;
		}

		if (null !== $this->offset) {
			$query->offset = $this->offset;
		}

		return $dataProvider;
	}
}
