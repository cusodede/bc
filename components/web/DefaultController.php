<?php
declare(strict_types = 1);

namespace app\components\web;

use app\components\helpers\ArrayHelper;
use app\models\sys\permissions\filters\PermissionFilter;
use app\models\sys\permissions\traits\ControllerPermissionsTrait;
use app\modules\import\models\ImportAction;
use app\modules\import\models\ImportStatusAction;
use cusodede\web\default_controller\models\DefaultController as VendorDefaultController;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Class DefaultController
 * Все контроллеры и все вью плюс-минус одинаковые, поэтому можно сэкономить на прототипировании
 */
class DefaultController extends VendorDefaultController {
	use ControllerPermissionsTrait;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'only' => ['ajax-search'],
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				]
			],
			[
				'class' => AjaxFilter::class,
				'only' => ['ajax-search']
			],
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * @inheritDoc
	 */
	public function actions():array {
		return ArrayHelper::merge(parent::actions(), [
			'editAction' => [
				'class' => EditableFieldAction::class,
				'modelClass' => $this->modelClass
			],
			'import' => [
				'class' => ImportAction::class,
				'modelClass' => $this->modelClass
			],
			'import-status' => [
				'class' => ImportStatusAction::class
			]
		]);
	}


}