<?php
declare(strict_types = 1);

namespace app\models\ticket\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\ticket\Ticket;
use pozitronik\helpers\DateHelper;
use pozitronik\helpers\Utils;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ticket_journal".
 *
 * @property string $id
 * @property string $ticket_id
 * @property int $operation_code
 * @property int $status
 * @property array $user_data Специфические данные для конкретного статуса
 * @property string $created_at
 *
 * @property Ticket $relatedTicket
 */
class TicketJournal extends ActiveRecord
{
	use ActiveRecordTrait;

	public const CODE_CREATED = 1000;

	public const STATUS_OK    = 0;
	public const STATUS_ERROR = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ticket_journal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['!id', 'ticket_id', 'operation_code', 'status'], 'required'],
            [['operation_code', 'status'], 'integer'],
            [['user_data', 'created_at'], 'safe'],
            [['!id', 'ticket_id'], 'string', 'max' => 36],
            [['!id'], 'unique'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
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
				'value' => static fn($event) => DateHelper::lcDate()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeValidate(): bool
	{
		if ($this->isNewRecord) {
			$this->id = Utils::gen_uuid();
		}

		return parent::beforeValidate();
	}

    /**
     * @return ActiveQuery
     */
    public function getRelatedTicket(): ActiveQuery
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
    }

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'ticket_id' => 'Ticket ID',
			'operation_code' => 'Operation Code',
			'status' => 'Status',
			'user_data' => 'User Data',
			'created_at' => 'Created At',
		];
	}
}
