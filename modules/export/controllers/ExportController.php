<?php
declare(strict_types = 1);

namespace app\modules\export\controllers;

use app\components\web\DefaultController;
use app\models\sys\permissions\filters\PermissionFilter;
use app\modules\export\models\SysExport;
use app\modules\export\models\SysExportSearch;
use Yii;

/**
 * Class ExportController
 */
class ExportController extends DefaultController {

	protected const DEFAULT_TITLE = 'Экспорт';
	public ?string $modelClass = SysExport::class;
	public ?string $modelSearchClass = SysExportSearch::class;
	public bool $enablePrototypeMenu = false;

	/** @inheritdoc */
	protected const ACTION_TITLES = [
		'pilot' => 'Пилот'
	];

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/modules/export/views/export';
	}

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * @param string $formName
	 * @param string $job
	 * @return string
	 */
	public function defaultAction(string $formName, string $job):string {
		$form = new $formName();
		if (Yii::$app->request->isPost) {
			Yii::$app->queue_common->push(new $job([
				'params' => Yii::$app->request->post($form->formName(), []),
				'user' => Yii::$app->user->id
			]));
			Yii::$app->session->setFlash('success');
		}
		return $this->render('export', ['form' => $form]);
	}
}
