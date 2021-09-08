<?php
declare(strict_types = 1);

namespace app\models\notification_templates;

use yii\data\ActiveDataProvider;

/**
 * Class NotificationTemplatesSearch
 * @package app\models\notification_templates
 */
class NotificationTemplatesSearch extends NotificationTemplates
{
	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['id', 'type', 'deleted'], 'integer'],
			[['message_body', 'subject', 'created_at', 'updated_at'], 'safe'],
		];
	}

	/**
	 * Creates data provider instance with search query applied
	 */
	public function search(array $params): ActiveDataProvider
	{
		$query = NotificationTemplates::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'type' => $this->type,
			'deleted' => $this->deleted,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		]);

		$query->andFilterWhere(['like', 'message_body', $this->message_body])
			->andFilterWhere(['like', 'subject', $this->subject]);

		return $dataProvider;
	}
}
