<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\partners\{PartnersSearch, Partners};
use pozitronik\core\traits\ControllerTrait;
use pozitronik\sys_exceptions\models\LoggedException;
use Throwable, Yii;
use yii\web\{Controller, NotFoundHttpException, Response};
use kartik\form\ActiveForm;

/**
 * Class PartnersController
 * @package app\controllers
 */
class PartnersController extends Controller
{
	use ControllerTrait;

	/**
	 * Все партнеры
	 * @return string
	 */
	public function actionIndex(): string
	{
		$params = Yii::$app->request->queryParams;
		$searchModel = new PartnersSearch();
		$dataProvider = $searchModel->search($params);
		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * Создание нового партнера
	 * @return string|Response|array
	 * @throws Throwable
	 */
	public function actionCreate()
	{
		$model = new Partners();
		// AJAX валидация на сервере с сохранением клиентской, в частности уникальный ИНН
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			$errors = ActiveForm::validate($model);
			if ($errors !== []) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return $errors;
			}
		}
		return $model->createModelFromPost() ? $this->redirect('index') : $this->renderAjax('modal/create', compact('model'));
	}

	/**
	 * Редактирование партнера
	 * @param int $id
	 * @return string|Response|array
	 * @throws LoggedException
	 * @throws Throwable
	 */
	public function actionEdit(int $id)
	{
		$model = Partners::findOne($id);
		if ($model === null) {
			throw new LoggedException(new NotFoundHttpException());
		}
		// AJAX валидация на сервере с сохранением клиентской, в частности уникальный ИНН
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			$errors = ActiveForm::validate($model);
			if ($errors !== []) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return $errors;
			}
		}
		return $model->updateModelFromPost() ? $this->redirect('index') : $this->renderAjax('modal/edit', compact('model'));
	}
}