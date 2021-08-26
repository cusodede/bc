<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\partners\fields;

use app\models\partners\Partners;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\partners\inputs\PartnersInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class PartnerUpdate
 * @package app\modules\graphql\schema\mutation\partners\fields
 */
class PartnerUpdate extends BaseMutationType
{
	public const MESSAGES = ['Ошибка сохранения партнера', 'Партнер успешно сохранен'];

	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'update',
			'description' => 'Обновление партнера',
			'type' => ResponseType::type(),
			'args' => [
				'id' => [
					'type' => Type::nonNull(Type::int()),
					'description' => 'Идентификатор партнёра',
				],
				'data' => [
					'type' => Type::nonNull(new PartnersInput('Update')),
				]
			],
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo): array => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		if (null === ($partner = Partners::findOne(ArrayHelper::getValue($args, 'id', 0)))) {
			throw new Exception("Не найдена модель для обновления.");
		}
		$data = ArrayHelper::getValue($args, 'data', []);
		$logo = ArrayHelper::getValue($data, 'logo');
		if (null !== $logo && preg_match('/http(s)?:/', $logo)) {
			unset($args['logo']);
		}
		return static::save($partner, $data, self::MESSAGES);
	}
}