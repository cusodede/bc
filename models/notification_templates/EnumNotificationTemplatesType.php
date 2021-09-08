<?php
declare(strict_types = 1);

namespace app\models\notification_templates;

use app\models\common\EnumTrait;

/**
 * Class EnumNotificationTemplatesType
 * @package app\models\notification_templates
 */
class EnumNotificationTemplatesType
{
	use EnumTrait;

	public const TYPE_EMAIL = 1;
	public const TYPE_SMS = 2;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::TYPE_EMAIL => 'Email',
			self::TYPE_SMS => 'sms',
		];
	}
}
