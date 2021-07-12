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
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rest\ActiveController;
use Throwable;

/**
 * Class GraphqlController
 * @package app\modules\graphql\controllers
 */
class GraphqlController extends ActiveController
{
	public $modelClass = '';

	/**
	 * @return array
	 */
	protected function verbs(): array
	{
		return [
			'index' => ['POST', 'OPTIONS'],
			'schema' => ['POST', 'OPTIONS'],
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
	 * Основная точка входа для GraphQL клиентов
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
	 * Точка входа на получение схемы для GraphQL клиентов.
	 * На фронте, есть некие "сборщики GraphQL схемы", которые,
	 * ходят по этому action только для формирования схемы, без авторизации.
	 * Отрубаем авторизацию и не даём выполнять никаких действий, просто отдаём схему.
	 * В schemaQuery, hardcode на получение схемы.
	 * Можно сделать лучше, но пока я не знаю как решить эту проблему.
	 * @return array
	 */
	public function actionSchema(): array
	{
		$query = file_get_contents(__DIR__ . '/../schemaQuery');
		return GraphQL::executeQuery(
			new Schema([
				'query' => QueryTypes::query(),
				'mutation' => MutationTypes::mutation(),
			]),
			$query,
		)->toArray();
	}
}