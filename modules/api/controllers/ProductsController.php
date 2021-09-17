<?php
declare(strict_types = 1);

namespace app\modules\api\controllers;

use app\components\subscription\SubscriptionTicketService;
use app\models\sys\permissions\filters\PermissionFilter;
use app\modules\api\exceptions\ValidationException;
use app\modules\api\models\ConnectSubscriptionTicketForm;
use app\modules\api\models\DisableSubscriptionTicketForm;
use app\modules\api\resources\formatters\ProductStoryFormatter;
use app\modules\api\resources\ProductsResource;
use cusodede\jwt\JwtHttpBearerAuth;
use Yii;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller as YiiRestController;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use Throwable;

/**
 * Class ProductsController
 * @package app\modules\api\controllers
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
				]
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
	 * Список продуктов.
	 * @param string $phone
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function actionList(string $phone): array
	{
		return (new ProductsResource())->getFullProductList($phone);
	}

	/**
	 * Информация о продукте.
	 * @param string $phone
	 * @param int $id
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function actionOne(string $phone, int $id): array
	{
		return (new ProductsResource())->getSingleProduct($phone, $id);
	}

	/**
	 * Список продуктов для сторис.
	 * @param string $phone
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function actionStories(string $phone): array
	{
		return (new ProductsResource(new ProductStoryFormatter()))->getFullProductList($phone);
	}

	/**
	 * Подключение подписки.
	 * @return array идентификатор созданного тикета.
	 * @throws ValidationException
	 * @throws Throwable
	 */
	public function actionConnect(): array
	{
		$form = new ConnectSubscriptionTicketForm();
		$form->load(Yii::$app->request->post(), '');
		if (!$form->validate()) {
			throw new ValidationException($form->errors);
		}

		return ['ticketId' => SubscriptionTicketService::getInstance()
			->createSubscribeTicket(
				$form->productId,
				$form->abonent->id
			)
		];
	}

	/**
	 * Отключение подписки.
	 * @return array идентификатор созданного тикета.
	 * @throws ValidationException
	 * @throws Throwable
	 */
	public function actionDisable(): array
	{
		$form = new DisableSubscriptionTicketForm();
		$form->load(Yii::$app->request->post(), '');
		if (!$form->validate()) {
			throw new ValidationException($form->errors);
		}

		return ['ticketId' => SubscriptionTicketService::getInstance()
			->createUnsubscribeTicket(
				$form->productId,
				$form->abonent->id
			)
		];
	}

	/**
	 * Получение статуса обработки тикета.
	 * @param string $ticketId идентификатор тикета.
	 * @return array
	 * @throws NotFoundHttpException
	 */
	public function actionTicketStatus(string $ticketId): array
	{
		return SubscriptionTicketService::getInstance()->getTicketStatus($ticketId);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return [
			'list' => ['GET'],
			'stories' => ['GET'],
			'one' => ['GET'],
			'ticket-status' => ['GET'],
			'connect' => ['POST'],
			'disable' => ['POST']
		];
	}
}