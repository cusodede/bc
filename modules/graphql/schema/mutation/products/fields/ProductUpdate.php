<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\products\fields;

use app\models\products\Products;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\products\inputs\ProductsInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class ProductUpdate
 * @package app\modules\graphql\schema\mutation\products\fields
 */
class ProductUpdate extends BaseMutationType
{
	public const MESSAGES = ['Ошибка сохранения продукта', 'Продукт успешно сохранен'];

	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'update',
			'description' => 'Обновление продукта',
			'type' => ResponseType::type(),
			'args' => [
				'id' => [
					'type' => Type::nonNull(Type::int()),
					'description' => 'Идентификатор продукта',
				],
				'data' => [
					'type' => Type::nonNull(new ProductsInput('Update')),
				]
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		if (null === ($partner = Products::findOne(ArrayHelper::getValue($args, 'id', 0)))) {
			throw new Exception("Не найдена модель для обновления.");
		}
		return static::save($partner, ArrayHelper::getValue($args, 'data', []), self::MESSAGES);
	}
}