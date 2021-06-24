<?php
declare(strict_types = 1);

namespace app\modules\api\controllers;

use app\components\tickets\ProductTicketsService;
use app\models\abonents\Abonents;
use app\models\sys\permissions\filters\PermissionFilter;
use app\modules\api\exceptions\ValidationException;
use app\modules\api\models\SubscribeProductTicketForm;
use app\modules\api\models\UnsubscribeProductTicketForm;
use app\modules\api\resources\ProductsResource;
use cusodede\jwt\JwtHttpBearerAuth;
use Yii;
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
			throw new NotFoundHttpException('Не удалось определить абонента по телефону: ' . $phone);
		}

		$resource = new ProductsResource();

		return $resource->getByAbonent($abonent);
	}

	/**
	 * Запрос на подключение продукта абоненту.
	 * @return array идентификатор созданного тикета.
	 * @throws ValidationException
	 */
	public function actionSubscribe(): array
	{
		$form = new SubscribeProductTicketForm();
		$form->load(Yii::$app->request->post(), '');
		if (!$form->validate()) {
			throw new ValidationException(current($form->errors));
		}

		$ticketService = new ProductTicketsService();

		return ['ticketId' => $ticketService->subscribe($form->productId, $form->abonent->id)];
	}

	/**
	 * Запрос на отключение продукта по абоненту.
	 * @return array идентификатор созданного тикета.
	 * @throws ValidationException
	 */
	public function actionUnsubscribe(): array
	{
		$form = new UnsubscribeProductTicketForm();
		$form->load(Yii::$app->request->post(), '');
		if (!$form->validate()) {
			throw new ValidationException(current($form->errors));
		}

		$ticketService = new ProductTicketsService();

		return ['ticketId' => $ticketService->unsubscribe($form->productId, $form->abonent->id)];
	}

	/**
	 * Получение статуса обработки тикета.
	 * @param string $ticketId идентификатор тикета.
	 * @return array
	 */
	public function actionGetTicketStatus(string $ticketId): array
	{
		$ticketService = new ProductTicketsService();

		return $ticketService->getTicketStatus($ticketId)->toArray();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return [
			'get-for-sub'       => ['GET'],
			'get-ticket-status' => ['GET'],
			'subscribe'         => ['POST'],
			'unsubscribe'       => ['POST']
		];
	}
}