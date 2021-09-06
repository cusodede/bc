<?php
declare(strict_types = 1);

namespace app\modules\api\controllers;

use app\components\tickets\ProductTicketsService;
use app\models\abonents\Abonents;
use app\models\sys\permissions\filters\PermissionFilter;
use app\modules\api\exceptions\ValidationException;
use app\modules\api\models\ConnectSubscriptionTicketForm;
use app\modules\api\models\DisableSubscriptionTicketForm;
use app\modules\api\resources\formatters\ProductStoryFormatter;
use app\modules\api\resources\ProductsResource;
use cusodede\jwt\JwtHttpBearerAuth;
use Exception;
use Throwable;
use Yii;
use yii\base\InvalidRouteException;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller as YiiRestController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ProductsController
 * @package app\modules\api\controllers
 */
class ProductsController extends YiiRestController
{
	/**
	 * @var Abonents|null модель абонента, соответствующая номеру телефона из параметров запроса.
	 */
	private ?Abonents $_abonent = null;

	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array
	{
		return [
			'contentNegotiator' => [
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				]
			],
			'verbFilter' => [
				'class' => VerbFilter::class,
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
	 * @param string $id
	 * @param array $params
	 * @return mixed
	 * @throws InvalidRouteException
	 * @throws NotFoundHttpException
	 */
	public function runAction($id, $params = []): mixed
	{
		$phone = $params['phone'] ?? null;
		if ((null !== $phone) && null === $this->_abonent = Abonents::findByPhone($phone)) {
			throw new NotFoundHttpException('Не удалось найти абонента по телефону: ' . $phone);
		}

		return parent::runAction($id, $params);
	}

	/**
	 * Список продуктов.
	 * @param string $phone
	 * @return array
	 * @noinspection PhpUnusedParameterInspection обязательный параметр, его обработка происходит перед непосредственным вызовом action'а.
	 * @see runAction()
	 */
	public function actionList(string $phone): array
	{
		return (new ProductsResource())->getFullProductList($this->_abonent);
	}

	/**
	 * Информация о продукте.
	 * @param string $phone
	 * @param int $id
	 * @return array
	 * @throws Exception
	 * @noinspection PhpUnusedParameterInspection обязательный параметр, его обработка происходит перед непосредственным вызовом action'а.
	 * @see runAction()
	 */
	public function actionOne(string $phone, int $id): array
	{
		return (new ProductsResource())->getSingleProduct($this->_abonent, $id);
	}

	/**
	 * Список продуктов для сторис.
	 * @param string $phone
	 * @return array
	 * @noinspection PhpUnusedParameterInspection обязательный параметр, его обработка происходит перед непосредственным вызовом action'а.
	 * @see runAction()
	 */
	public function actionStories(string $phone): array
	{
		return (new ProductsResource(new ProductStoryFormatter()))->getFullProductList($this->_abonent);
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

		return ['ticketId' => (new ProductTicketsService())->createSubscribeTicket($form->productId, $form->abonent->id)];
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

		return ['ticketId' => (new ProductTicketsService())->createUnsubscribeTicket($form->productId, $form->abonent->id)];
	}

	/**
	 * Получение статуса обработки тикета.
	 * @param string $ticketId идентификатор тикета.
	 * @return array
	 * @throws NotFoundHttpException
	 */
	public function actionTicketStatus(string $ticketId): array
	{
		return ProductTicketsService::getTicketStatus($ticketId);
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