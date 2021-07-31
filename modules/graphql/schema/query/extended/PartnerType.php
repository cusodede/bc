<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended;

use app\models\partners\Partners;
use app\models\partners\PartnersSearch;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\QueryTypes;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnerType
 * @package app\modules\graphql\schema\query\extended
 */
final class PartnerType extends BaseQueryType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнера',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование юридического лица партнера',
				],
				'inn' => [
					'type' => Type::string(),
					'description' => 'ИНН партнера',
				],
				'phone' => [
					'type' => Type::string(),
					'description' => 'Телефон поддержки партнера',
				],
				'email' => [
					'type' => Type::string(),
					'description' => 'Почтовый адрес поддержки партнера',
				],
				'comment' => [
					'type' => Type::string(),
					'description' => 'Комментарий',
				],
				'category_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор категории',
				],
				'logo' => [
					'type' => Type::string(),
					'description' => 'Логотип партнёра',
				],
				'category' => [
					'type' => QueryTypes::partnerCategory(),
					'description' => 'Категория партнера',
					'resolve' => fn(Partners $partner) => $partner->relatedCategory,
				],
			],
		]);
	}

	/**
	 * @return array
	 */
	public static function getListOfType(): array
	{
		return [
			'type' => Type::listOf(QueryTypes::partner()),
			'args' => [
				'search' => Type::string(),
			],
			'description' => 'Возвращаем список партнёров',
			'resolve' => fn(Partners $partner = null, array $args = []): ?array => PartnersSearch::searchWithParams($args),
		];
	}

	/**
	 * @return array
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => QueryTypes::partner(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает партнёра по id',
			'resolve' => fn(Partners $partner = null, array $args = []): ?Partners => Partners::find()->where($args)->active()->one(),
		];
	}
}
