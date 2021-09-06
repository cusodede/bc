<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\db\ActiveRecordTrait;
use app\components\web\DefaultController;
use app\models\subscriptions\Subscriptions;
use app\models\subscriptions\SubscriptionsSearch;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SubscriptionsController
 * @package app\controllers
 */
class SubscriptionsController extends DefaultController
{
	/**
	 * Поисковая модель подписок
	 * @var string
	 */
	public string $modelSearchClass = SubscriptionsSearch::class;

	/**
	 * Модель подписок
	 * @var string
	 */
	public string $modelClass = Subscriptions::class;

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath(): string
	{
		return '@app/views/subscriptions';
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate()
	{
		/** @var Subscriptions $model */
		$model = $this->model;
		if (Yii::$app->request->post('ajax')) {/* запрос на ajax-валидацию формы */
			return $this->asJson($model->validateModelFromPost());
		}
		$errors  = [];
		$posting = $model->createModelFromPost($errors, null, ['product']);//todo: релейшены можно вычислять из свойств модели
		if (true === $posting) {/* Модель была успешно прогружена */
			return $this->redirect('index');
		}
		/* Пришёл постинг, но есть ошибки */
		if ((false === $posting) && Yii::$app->request->isAjax) {
			return $this->asJson($errors);
		}
		/* Постинга не было */
		return (Yii::$app->request->isAjax)
			? $this->renderAjax('modal/create', ['model' => $model])
			: $this->render('create', ['model' => $model]);
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionEdit(int $id)
	{
		if (null === $model = $this->model::findOne($id)) {
			throw new NotFoundHttpException();
		}

		/** @var ActiveRecordTrait $model */
		if (Yii::$app->request->post('ajax')) {/* запрос на ajax-валидацию формы */
			return $this->asJson($model->validateModelFromPost());
		}
		$errors  = [];
		$posting = $model->updateModelFromPost($errors, null, ['product']);

		if (true === $posting) {/* Модель была успешно прогружена */
			return $this->redirect('index');
		}
		/* Пришёл постинг, но есть ошибки */
		if ((false === $posting) && Yii::$app->request->isAjax) {
			return $this->asJson($errors);
		}
		/* Постинга не было */
		return (Yii::$app->request->isAjax)
			? $this->renderAjax('modal/edit', ['model' => $model])
			: $this->render('edit', ['model' => $model]);
	}

}