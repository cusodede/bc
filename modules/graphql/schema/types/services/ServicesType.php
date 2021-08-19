<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\services;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\services\fields\ServerDateTimeField;

/**
 * Class ServicesType
 * @package app\modules\graphql\schema\types\services
 */
class ServicesType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Вспомогательные, сервисные сущности',
			'fields' => [
				'serverDateTime' => ServerDateTimeField::field(),
			]
		]);
	}
}