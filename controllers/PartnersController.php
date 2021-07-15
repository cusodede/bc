<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\partners\PartnersSearch;
use app\models\partners\Partners;
use Yii;
use yii\web\NotFoundHttpException;

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
	 * Скачивание логотипа партнера.
	 * @param int $id
	 * @throws NotFoundHttpException
	 */
	public function actionGetLogo(int $id): void
	{
		if (null === $partner = Partners::findOne($id)) {
			throw new NotFoundHttpException();
		}

		Yii::$app->response->sendFile($partner->fileLogo->path);
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