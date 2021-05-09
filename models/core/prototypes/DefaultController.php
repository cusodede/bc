<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use pozitronik\core\traits\ControllerTrait;
use pozitronik\sys_exceptions\models\LoggedException;
use Throwable;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DefaultController
 * Все контроллеры и все вью плюс-минус одинаковые, поэтому можно сэкономить на прототипировании
 * @property Model|string $modelClass Модель, обслуживаемая контроллером
 * @property Model|string $modelSearchClass Поисковая модель, обслуживаемая контроллером
 */
class DefaultController extends Controller {
	use ControllerTrait;

	/**
	 * @var string $modelClass
	 */
	public $modelClass;
	/**
	 * @var string $modelSearchClass
	 */
	public $modelSearchClass;

	/**
	 * @inheritDoc
	 */
	public function getViewPath() {
		return '@app/views/default';
	}

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new $this->modelSearchClass();

		return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $searchModel->search($params),
				'controller' => $this,
				'modelName' => (new $this->modelClass)->formName()
			]
		);
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionEdit(int $id) {
		if (null === $model = $this->modelClass::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}

		if ($model->updateModelFromPost()) return $this->redirect('index');

		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/edit', [
				'model' => $model
			]);
		}
		return $this->render('edit', [
			'model' => $model
		]);
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate() {
		$model = new $this->modelClass();
		if ($model->createModelFromPost()) {
			return $this->redirect('index');
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/create', [
				'model' => $model
			]);
		}
		return $this->render('create', [
			'model' => $model
		]);
	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws Throwable
	 */
	public function actionDelete(int $id):Response {
		if (null === $model = $this->modelClass::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}
		$model->safeDelete();
		return $this->redirect('index');
	}
}