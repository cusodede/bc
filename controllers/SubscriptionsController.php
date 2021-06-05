<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\products\Products;
use app\models\ref_products_types\RefProductsTypes;
use app\models\subscriptions\Subscriptions;
use app\models\subscriptions\SubscriptionsSearch;
use pozitronik\core\traits\ControllerTrait;
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
	use ControllerTrait;

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
	 * Создание двух пустых моделей (продукт и подписка).
	 * Новая подписка, по сути - новый продукт с дополнительными атрибутами.
	 * Транзакции нам не помогут, так как надо сохранить модель продукта,
	 * до того как, сохранить модель подписки, для связки по product_id.
	 * Поэтому предохраняемся валидацией ActiveForm::validate(), а потом сохраняем.
	 * @return string|Response
	 */
	public function actionCreate()
	{
		// Исключаем product_id из валидации
		$subscription = new Subscriptions(['scenario' => Subscriptions::SCENARIO_CREATE_AJAX]);
		$product = new Products();

		if (Yii::$app->request->post('ajax')) {
			$subscription->load(Yii::$app->request->post());
			$product->load(Yii::$app->request->post());
			$product->type_id = RefProductsTypes::ID_SUBSCRIPTION; // Определяем, что это подписка

			$errors = ArrayHelper::merge(ActiveForm::validate($product), ActiveForm::validate($subscription));
			if ([] === $errors) {
				return $this->asJson($errors);
			}

			$product->save();
			$subscription->link('product', $product); // Цепляем к партнеру и сохраняем

			return $this->redirect('index');
		}

		return $this->renderAjax('modal/create', compact('subscription', 'product'));
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

		$result = Yii::$app->request->post('ajax')
			&& $subscription->load(Yii::$app->request->post())
			&& $product->load(Yii::$app->request->post());

		if ($result) {
			$errors = ArrayHelper::merge(ActiveForm::validate($product), ActiveForm::validate($subscription));
			if ($errors !== []) {
				return $this->asJson($errors);
			}
			$product->save();
			$subscription->link('product', $product); // Цепляем к партнеру и сохраняем
			return $this->redirect('index');
		}

		return $this->renderAjax('modal/edit', compact('subscription', 'product'));
	}
}