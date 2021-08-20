<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners\fields;

use app\models\partners\Partners;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\partners\PartnerType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class PartnerProfileField
 * @package app\modules\graphql\schema\types\partners\fields
 */
class PartnerProfileField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'partnerProfile',
			'type' => PartnerType::type(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает партнёра по идентификатору.',
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo) => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?ActiveRecord
	{
		return Partners::findOne(ArrayHelper::getValue($args, 'id', 0));
	}
}