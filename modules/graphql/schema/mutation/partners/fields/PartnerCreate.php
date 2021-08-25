<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\partners\fields;

use app\models\partners\Partners;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\partners\inputs\PartnersInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class PartnerCreate
 * @package app\modules\graphql\schema\mutation\partners\fields
 */
class PartnerCreate extends BaseMutationType
{
	public const MESSAGES = ['Ошибка сохранения партнера', 'Партнер успешно сохранен'];

	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'create',
			'description' => 'Создание партнёра',
			'type' => ResponseType::type(),
			'args' => [
				'data' => [
					'type' => Type::nonNull(new PartnersInput('Create')),
				]
			],
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo): array => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?array
	{
		return static::save(new Partners(), ArrayHelper::getValue($args, 'data', []), self::MESSAGES);
	}
}