<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\fields;

use app\models\products\Products;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\products\ProductType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ProductProfileField
 * @package app\modules\graphql\schema\types\products\fields
 */
class ProductProfileField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'productProfile',
			'type' => ProductType::type(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает продукт по идентификатору.',
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo) => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?ActiveRecord
	{
		return Products::findOne(ArrayHelper::getValue($args, 'id', 0));
	}
}