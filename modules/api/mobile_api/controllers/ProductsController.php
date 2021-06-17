<?php
declare(strict_types = 1);

namespace app\modules\api\mobile_api\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use app\modules\api\mobile_api\resources\ProductsResource;
use cusodede\jwt\JwtHttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller as YiiRestController;
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

	public function actionIndex(string $phone): array
	{
		$resource = new ProductsResource();

		return $resource->getProductsByPhone($phone);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return [
			'index' => ['GET']
		];
	}
}