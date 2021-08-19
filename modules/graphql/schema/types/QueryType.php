<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\modules\graphql\components\BaseObjectType;

/**
 * Class QueryType
 * @package app\schema
 */
class QueryType extends BaseObjectType {
	/**
	 * {@inheritdoc}
	 */
	public function __construct() {
		$schema = [

		];
		ksort($schema, SORT_REGULAR);

		parent::__construct([
			'fields' => $schema,
		]);
	}
}
