<?php
declare(strict_types = 1);

namespace app\controllers\actions\swagger;

use yii\base\Action;

/**
 * Class SwaggerUiAction
 * @package app\controllers\actions
 */
class SwaggerUiAction extends Action
{
	/**
	 * @var string|null URL для получения схемы.
	 */
	public ?string $schemaUrl = null;

	/**
	 * @return string
	 */
	public function run(): string
	{
		$this->controller->layout = false;

		return $this->controller->render('swagger-doc', ['schemaUrl' => $this->schemaUrl]);
	}
}