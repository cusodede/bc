<?php
declare(strict_types = 1);

namespace app\models\refsharing_rates;


use yii\data\ActiveDataProvider;


/**
 * Class RevShareSearch
 * @package app\models\contracts
 */
class RevShareSearch extends RevShare
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'product_id', 'value', 'deleted'], 'integer'],
            [['type', 'ref_share', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = RevShare::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'type' => $this->type,
            'ref_share' => $this->ref_share,
            'value' => $this->value,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
