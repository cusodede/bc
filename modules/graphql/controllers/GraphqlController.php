<?php
declare(strict_types = 1);

namespace app\modules\graphql\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use app\modules\graphql\data\MutationTypes;
use app\modules\graphql\data\QueryTypes;
use cusodede\jwt\JwtHttpBearerAuth;
use Exception;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Utils\SchemaPrinter;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rest\ActiveController;
use Throwable;
use yii\web\Response;

/**
 * Class GraphqlController
 * @package app\modules\graphql\controllers
 */
class GraphqlController extends ActiveController
{
	public $modelClass = '';
	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return [
			'index' => ['POST', 'OPTIONS']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => PermissionFilter::class,
				'except' => ['schema'],
			],
			'authenticator' => [
				'class' => JwtHttpBearerAuth::class,
				'except' => ['schema'],
			],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions(): array
	{
		return [];
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function actionIndex(): array
	{
		$query = Yii::$app->request->get('query', Yii::$app->request->post('query'));
		$variables = Yii::$app->request->get('variables', Yii::$app->request->post('variables'));
		$operation = Yii::$app->request->get('operation', Yii::$app->request->post('operation'));

		if (null === $query) {
			$rawInput = file_get_contents('php://input');
			$input = Json::decode($rawInput);
			$query = ArrayHelper::getValue($input, 'query');
			$variables = ArrayHelper::getValue($input, 'variables', []);
			$operation = ArrayHelper::getValue($input, 'operation');
		}

		if (!empty($variables) && !is_array($variables)) {
			try {
				$variables = Json::decode($variables);
			} /** @noinspection BadExceptionsProcessingInspection Это норма */ catch (Throwable $t) {
				$variables = null;
			}
		}

		return GraphQL::executeQuery(
			new Schema([
				'query' => QueryTypes::query(),
				'mutation' => MutationTypes::mutation(),
			]),
			$query,
			null,
			null,
			$variables ?: null,
			$operation ?: null
		)->toArray(YII_DEBUG ? DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE : DebugFlag::NONE);
	}

	/**
	 * @return string
	 */
	public function actionSchema(): string
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		return SchemaPrinter::doPrint(
			new Schema([
				'query' => QueryTypes::query(),
				'mutation' => MutationTypes::mutation(),
			])
		);
	}
}