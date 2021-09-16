<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\abonents\fields;

use app\models\abonents\AbonentsSearch;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\abonents\AbonentType;
use app\modules\graphql\schema\types\abonents\inputs\AbonentsFilterInput;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Журнал подписок абонента.
 */
class AbonentsListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'abonentList',
			'type' => Type::listOf(AbonentType::type()),
			'description' => 'Список абонентов',
			'args' => [
				'filters' => [
					'type' => new AbonentsFilterInput(),
				],
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		$abonentsSearch = new AbonentsSearch();
		$filters = ArrayHelper::getValue($args, 'filters', []);
		ArrayHelper::setValue($args, 'pagination', false);
		return $abonentsSearch->search([$abonentsSearch->formName() => ArrayHelper::merge($args, $filters)])->getModels();
	}
}