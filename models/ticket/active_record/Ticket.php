<?php
declare(strict_types = 1);

namespace app\models\ticket\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\ticket\TicketTrait;
use app\models\ticket\TicketProductSubscription;
use pozitronik\helpers\DateHelper;
use pozitronik\helpers\Utils;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ticket".
 *
 * @property string $id
 * @property int $type
 * @property int $created_by
 * @property string $created_at
 * @property string|null $completed_at
 *
 * @property TicketJournal[] $relatedTicketJournals
 * @property TicketProductSubscription $relatedTicketProductSubscription
 */
class Ticket extends ActiveRecord
{
	use ActiveRecordTrait;
	use TicketTrait;

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
            [['!id', 'type'], 'required'],
            [['type', 'created_by'], 'integer'],
            [['created_at', 'completed_at'], 'safe'],
            [['!id'], 'string', 'max' => 36],
            [['!id'], 'unique'],
        ];
    }

	/**
	 * {@inheritdoc}
	 */
	public function behaviors():array {
		return ArrayHelper::merge(parent::behaviors(), [
			[
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
				'value' => static function($event) {
					return DateHelper::lcDate();
				}
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
        return $this->hasOne(TicketProductSubscription::class, ['id' => 'id']);
    }

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'type' => 'Type',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'completed_at' => 'Completed At'
		];
	}
}
