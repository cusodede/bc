<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners;

use app\controllers\PartnersController;
use app\models\common\RefPartnersCategories;
use app\models\partners\Partners;
use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnerType
 * @package app\modules\graphql\schema\types\partners
 */
class PartnerType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Партнёр',
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
					'resolve' => fn(Partners $partner) => PartnersController::to('get-logo', ['id' => $partner->id], true)
				],
				'category' => [
					'type' => PartnerCategoryType::type(),
					'description' => 'Категория партнера',
					'resolve' => fn(Partners $partner):RefPartnersCategories => $partner->relatedCategory,
				],
			],
		]);
	}
}