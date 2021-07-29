<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended\enum;

use GraphQL\Type\Definition\EnumType;

/**
 * Class FormPartnersType
 * @package app\modules\graphql\schema\query\extended\enum
 */
class FormPartnersType extends EnumType
{
	public const NAME			= 'name';
	public const INN			= 'inn';
	public const PHONE 			= 'phone';
	public const EMAIL			= 'email';
	public const COMMENT		= 'comment';
	public const CATEGORY_ID	= 'category_id';
	public const LOGO			= 'logo';

	public function __construct()
	{
		parent::__construct([
			'name' => 'FormPartnersField',
			'values' => [
				self::NAME,
				self::INN,
				self::PHONE,
				self::EMAIL,
				self::COMMENT,
				self::CATEGORY_ID,
				self::LOGO,
			],
		]);
	}
}
