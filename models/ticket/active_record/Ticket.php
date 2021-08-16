<?php
declare(strict_types = 1);

namespace app\models\ticket\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\ticket\Ticket as TicketExtended;
use app\models\ticket\TicketSubscription;
use pozitronik\helpers\DateHelper;
use pozitronik\helpers\Utils;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ticket".
 *
 * @property string $id
 * @property int $type
 * @property int $stage_id [int]
 * @property int $status [smallint]
 * @property array $journal_data [json]
 * @property int $created_by
 * @property string $created_at
 * @property string|null $completed_at
 *
 * @property TicketSubscription $relatedTicketProductSubscription
 */
class Ticket extends ActiveRecord
{
	use ActiveRecordTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
			[['status'], 'default', 'value' => TicketExtended::STATUS_OK],
            [['type', 'stage_id', 'status', '!id'], 'required'],
            [['type', 'stage_id', 'status', 'created_by'], 'integer'],
			[['journal_data'], 'default', 'value' => []],
            [['journal_data', 'created_at', 'completed_at'], 'safe'],
            [['!id'], 'string', 'max' => 36],
            [['!id'], 'unique'],
        ];
    }

	/**
	 * {@inheritdoc}
	 */
	public function behaviors():array {
		return array_merge(parent::behaviors(), [
			[
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
				'value' => static fn() => DateHelper::lcDate()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
    public function beforeValidate(): bool
	{
		if ($this->isNewRecord && empty($this->id)) {
			$this->id = Utils::gen_uuid();
		}

		return parent::beforeValidate();
	}

    /**
     * @return ActiveQuery
     */
    public function getRelatedTicketProductSubscription(): ActiveQuery
    {
        return $this->hasOne(TicketSubscription::class, ['id' => 'id']);
    }
}
