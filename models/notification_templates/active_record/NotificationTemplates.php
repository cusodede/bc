<?php
declare(strict_types = 1);

namespace app\models\notification_templates\active_record;

use app\components\db\ActiveRecordTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notification_templates".
 *
 * @property int $id
 * @property int $type Типа (mail, sms)
 * @property string $message_body Тело сообщения
 * @property string $subject Тема письма
 * @property int $deleted Флаг активности
 * @property string $created_at Дата создания шаблона
 * @property string $updated_at Дата обновления шаблона
 */
class NotificationTemplates extends ActiveRecord
{
	use ActiveRecordTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'notification_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type', 'message_body'], 'required'],
            [['type', 'deleted'], 'integer'],
            [['message_body'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
			'subject' => 'Тема',
			'message_body' => 'Сообщение',
            'deleted' => 'Флаг удаления',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
