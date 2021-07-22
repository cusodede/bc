<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\partners\PartnersSearch;
use app\models\partners\Partners;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class PartnersController
 * @package app\controllers
 */
class PartnersController extends DefaultController
{

	/**
	 * Поисковая модель партнера
	 * @var string
	 */
	public string $modelSearchClass = PartnersSearch::class;

	/**
	 * Модель партнера
	 * @var string
	 */
	public string $modelClass = Partners::class;

	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array
	{
		$parent = parent::behaviors();
		$parent['access']['except'] = ['get-logo'];

		return $parent;
	}

	/**
	 * Скачивание логотипа партнера.
	 * @param int $id
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function actionGetLogo(int $id): Response
	{
		if (null === $partner = Partners::findOne($id)) {
			throw new NotFoundHttpException();
		}

		return Yii::$app->response->sendFile($partner->fileLogo->path);
	}

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath(): string
	{
		return '@app/views/partners';
	}
}