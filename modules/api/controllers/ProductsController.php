<?php
declare(strict_types = 1);

namespace app\modules\api\controllers;

use app\models\abonents\Abonents;
use app\models\sys\permissions\filters\PermissionFilter;
use app\modules\api\resources\ProductsResource;
use cusodede\jwt\JwtHttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller as YiiRestController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SubscriptionsController
 * @package app\modules\api\mybeeline\controllers
 */
class ProductsController extends YiiRestController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array
	{
		return [
			'contentNegotiator' => [
				'class'   => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				],
			],
			'verbFilter' => [
				'class'   => VerbFilter::class,
				'actions' => $this->verbs(),
			],
			'authenticator' => [
				'class' => JwtHttpBearerAuth::class
			],
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * Получением списка продуктов по номеру абонента.
	 * @param string $phone
	 * @return array
	 * @throws NotFoundHttpException
	 */
	public function actionGetForSub(string $phone): array
	{
		$abonent = Abonents::findByPhone($phone);
		if (null === $abonent) {
			throw new NotFoundHttpException('Не удалось установить абонента по телефону: ' . $phone);
		}

		$resource = new ProductsResource();

		return $resource->getAbonentProducts($abonent);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return [
			'get-for-sub' => ['GET']
		];
	}
}