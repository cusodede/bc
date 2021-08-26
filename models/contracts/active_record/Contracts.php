<?php
declare(strict_types = 1);

namespace app\models\contracts\active_record;

use app\components\db\ActiveRecordTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "contracts".
 *
 * @property int $id
 * @property string $contract_number № договора
 * @property string $contract_number_nfs № контракта
 * @property string $signing_date Дата подписания договора
 * @property int $deleted Флаг активности
 * @property string $created_at Дата создания договора
 * @property string $updated_at Дата обновления договора
 */
class Contracts extends ActiveRecord
{
	use ActiveRecordTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'contracts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['contract_number', 'contract_number_nfs'], 'required'],
            [['deleted'], 'integer'],
            [['signing_date', 'created_at', 'updated_at'], 'safe'],
            [['contract_number', 'contract_number_nfs'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'contract_number' => 'Contract Number',
            'contract_number_nfs' => 'Contract Number Nfs',
            'signing_date' => 'Signing Date',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedContractsToProducts(): ActiveQuery
	{
		return $this->hasMany(RelContractsToProducts::class, ['contract_id' => 'id']);
	}

}
