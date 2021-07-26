<?php
declare(strict_types = 1);

namespace app\controllers\actions\swagger;

use Yii;
use yii\base\Action;
use yii\web\Response;

/**
 * Swagger UI будет ходить сюда для запроса схемы.
 * Class SchemaAction
 * @package app\controllers\actions
 */
class SchemaAction extends Action
{
	/**
	 * @return Response
	 */
	public function run(): Response
	{
		return Yii::$app->response->sendFile(Yii::getAlias('@app/modules/api/swagger/schema.yml'));
	}
}