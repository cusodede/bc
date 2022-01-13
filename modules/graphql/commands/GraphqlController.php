<?php
declare(strict_types = 1);

namespace app\modules\graphql\commands;

use app\modules\graphql\schema\types\MutationType;
use app\modules\graphql\schema\types\QueryType;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Throwable;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Json;

/**
 * Class GraphqlController
 */
class GraphqlController extends Controller {
	/* Дефолтный запрос, выполняемый при вызове без параметров */
	private const DEFAULT_INTROSPECTION_QUERY = "query IntrospectionQuery{__schema{queryType{name}mutationType{name}subscriptionType{name}types{...FullType}directives{name description locations args{...InputValue}}}}fragment FullType on __Type{kind name description fields(includeDeprecated:true){name description args{...InputValue}type{...TypeRef}isDeprecated deprecationReason}inputFields{...InputValue}interfaces{...TypeRef}enumValues(includeDeprecated:true){name description isDeprecated deprecationReason}possibleTypes{...TypeRef}}fragment InputValue on __InputValue{name description type{...TypeRef}defaultValue}fragment TypeRef on __Type{kind name ofType{kind name ofType{kind name ofType{kind name ofType{kind name ofType{kind name ofType{kind name ofType{kind name}}}}}}}}";

	/**
	 * @param string|null $query Query string
	 * @param string|null $variables Variables array in JSON string
	 * @param string|null $operation Operation name
	 * @return void
	 * @throws Throwable
	 */
	public function actionIndex(?string $query = self::DEFAULT_INTROSPECTION_QUERY, ?string $variables = null, ?string $operation = null):void {
		Console::output(Json::encode(GraphQL::executeQuery(
			new Schema([
				'query' => QueryType::type(),
				'mutation' => MutationType::type(),
			]),
			$query,
			null,
			null,
			Json::decode($variables)?:null,
			$operation?:null
		)->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE)));
	}

}
