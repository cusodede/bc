<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use pozitronik\dbmon\models\DbMonitor;
use pozitronik\traits\traits\ControllerTrait;
use Throwable;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class DbController
 */
class DbController extends Controller
{
	use ControllerTrait;

	/**
	 * @inheritDoc
	 */
	public function behaviors(): array
	{
		return [
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * Список процессов на базе данных
	 * @return string
	 * @throws Throwable
	 */
	public function actionProcessList(): string
	{
		$provider = new ArrayDataProvider([
			'allModels' => DbMonitor::ProcessList(),
			'sort' => [
				'attributes' => ['id', 'user_id', 'time'],
				'defaultOrder' => ['time' => SORT_DESC]
			]
		]);
		return $this->render('process-list', [
			'dataProvider' => $provider,
			'message' => Yii::$app->session->getFlash('DbMonitorMessage', false, true)
		]);
	}

	/**
	 * @param int $process_id
	 * @return Response
	 */
	public function actionKill(int $process_id): Response
	{
		Yii::$app->session->setFlash('DbMonitorMessage', (null === $affected = DbMonitor::kill($process_id)) ? "None killed" : "{$affected} row(s) $affected");
		return $this->redirect(['process-list']);
	}
}
