<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\products\Products;
use app\models\ref_products_types\RefProductsTypes;
use app\models\subscriptions\Subscriptions;
use app\models\subscriptions\SubscriptionsSearch;
use pozitronik\sys_exceptions\models\LoggedException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

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
	 */
	public function actionCreate()
	{
		$subscription = new Subscriptions(['scenario' => Subscriptions::SCENARIO_CREATE_AJAX]);
		$product = new Products();
		$product->type_id = RefProductsTypes::ID_SUBSCRIPTION; // Определяем, что это подписка

		$postingProduct = $product->load(Yii::$app->request->post());
		$postingSubscription = $subscription->load(Yii::$app->request->post());

		$errors = ArrayHelper::merge(ActiveForm::validate($product), ActiveForm::validate($subscription));
		if ([] !== $errors && Yii::$app->request->isAjax && true === $postingSubscription && true === $postingProduct) {
			return $this->asJson($errors);
		}

		if (true === $postingProduct && true === $postingSubscription) {
			$subscription->product = $product;
			if ($subscription->save()) {
				return $this->redirect('index');
			}
			if (Yii::$app->request->isAjax) {
				return $this->asJson($errors);
			}
		}

		return Yii::$app->request->isAjax ?
			$this->renderAjax('modal/create', compact('subscription', 'product')) :
			$this->render('create', compact('subscription', 'product'));
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws LoggedException
	 */
	public function actionEdit(int $id)
	{
		$subscription = Subscriptions::findOne($id);
		if (null === $subscription) {
			throw new LoggedException(new NotFoundHttpException());
		}

		$product = Products::findOne($subscription->product_id);
		if (null === $product) {
			throw new LoggedException(new NotFoundHttpException());
		}

		$postingProduct = $product->load(Yii::$app->request->post());
		$postingSubscription = $subscription->load(Yii::$app->request->post());

		$errors = ArrayHelper::merge(ActiveForm::validate($product), ActiveForm::validate($subscription));
		if ([] !== $errors && Yii::$app->request->isAjax && true === $postingSubscription && true === $postingProduct) {
			return $this->asJson($errors);
		}

		if (true === $postingProduct && true === $postingSubscription) {
			$subscription->product = $product;
			if ($subscription->save()) {
				return $this->redirect('index');
			}
			if (Yii::$app->request->isAjax) {
				return $this->asJson($errors);
			}
		}

		return Yii::$app->request->isAjax ?
			$this->renderAjax('modal/edit', compact('subscription', 'product')) :
			$this->render('edit', compact('subscription', 'product'));
	}
}