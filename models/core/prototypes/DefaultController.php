<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use pozitronik\core\helpers\ControllerHelper;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\sys_exceptions\models\LoggedException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
use yii\db\ActiveRecord;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DefaultController
 * Все контроллеры и все вью плюс-минус одинаковые, поэтому можно сэкономить на прототипировании
 * @property string $modelClass Модель, обслуживаемая контроллером
 * @property string $modelSearchClass Поисковая модель, обслуживаемая контроллером
 *
 * @property-read ActiveRecord $searchModel
 * @property-read ActiveRecord|ActiveRecordTrait $model
 */
class DefaultController extends Controller {
	use ControllerTrait;

	/**
	 * @var string $modelClass
	 */
	public string $modelClass;
	/**
	 * @var string $modelSearchClass
	 */
	public string $modelSearchClass;

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
			]
		];
	}

	/**
	 * Генерирует меню для доступа ко всем контроллерам по указанному пути
	 * @param string $alias
	 * @return array
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function MenuItems(string $alias = "@app/controllers"):array {
		$items = [];
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias($alias)), RecursiveIteratorIterator::SELF_FIRST);
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			/** @var self $model */
			if ($file->isFile() && 'php' === $file->getExtension() && null !== $model = ControllerHelper::LoadControllerClassFromFile($file->getRealPath(), null, [self::class])) {
				$items[] = [
					'label' => $model->id,
					'url' => $model::to($model->defaultAction)
				];
			}
		}
		return $items;
	}

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/default';
	}

	/**
	 * @return ActiveRecord|ActiveRecordTrait
	 */
	public function getModel():ActiveRecord {
		return (new $this->modelClass());
	}

	/**
	 * @return ActiveRecord
	 */
	public function getSearchModel():ActiveRecord {
		return (new $this->modelSearchClass());
	}

	/**
	 * @return string
	 * @throws InvalidConfigException
	 * @noinspection PhpPossiblePolymorphicInvocationInspection
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = $this->searchModel;

		return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $searchModel->search($params),
				'controller' => $this,
				'modelName' => $this->model->formName()
			]
		);
	}

	/**
	 * @param int $id
	 * @return string
	 * @throws Throwable
	 */
	public function actionView(int $id):string {
		if (null === $model = $this->model::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/view', [
				'model' => $model
			]);
		}
		return $this->render('view', [
			'model' => $model
		]);
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionEdit(int $id) {
		if (null === $model = $this->model::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}

		/** @var ActiveRecordTrait $model */
		if (Yii::$app->request->post('ajax')) {/* запрос на ajax-валидацию формы */
			return $this->asJson($model->validateModelFromPost());
		}

		$posting = $model->updateModelFromPost([], $errors);

		if (true === $posting) {/* Модель была успешно прогружена */
			return $this->redirect('index');
		}
		/* Пришёл постинг, но есть ошибки */
		if ((false === $posting) && Yii::$app->request->isAjax) {
			return $this->asJson($errors);
		}
		/* Постинга не было */
		return (Yii::$app->request->isAjax)
			?$this->renderAjax('modal/edit', ['model' => $model])
			:$this->render('edit', ['model' => $model]);
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate() {
		$model = $this->model;
		if (Yii::$app->request->post('ajax')) {/* запрос на ajax-валидацию формы */
			return $this->asJson($model->validateModelFromPost());
		}
		$posting = $model->createModelFromPost([], $errors);/* switch тут нельзя использовать из-за его нестрогости */
		if (true === $posting) {/* Модель была успешно прогружена */
			return $this->redirect('index');
		}
		/* Пришёл постинг, но есть ошибки */
		if ((false === $posting) && Yii::$app->request->isAjax) {
			return $this->asJson($errors);
		}
		/* Постинга не было */
		return (Yii::$app->request->isAjax)
			?$this->renderAjax('modal/create', ['model' => $model])
			:$this->render('create', ['model' => $model]);
	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws Throwable
	 */
	public function actionDelete(int $id):Response {
		if (null === $model = $this->model::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}
		/** @noinspection PhpUndefinedMethodInspection */
		$model->safeDelete();
		return $this->redirect('index');
	}

	/**
	 * Аяксовый поиск в Select2
	 * @param string|null $term
	 * @return string[][]
	 */
	public function actionAjaxSearch(?string $term):array {
		$out = [
			'results' => [
				'id' => '',
				'text' => ''
			]
		];
		if (null !== $term) {
			$tableName = $this->model::tableName();
			$data = $this->model::find()
				->select(["{$tableName}.id", "{$tableName}.name as text"])
				->where(['like', "{$tableName}.name", "%$term%", false])
				->active()
				->distinct()
				->asArray()
				->all();
			$out['results'] = array_values($data);
		}
		return $out;
	}

}