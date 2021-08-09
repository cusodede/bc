<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\tickets\ProductTicketsService;
use app\models\abonents\Abonents;
use app\models\sys\permissions\filters\PermissionFilter;
use pozitronik\traits\traits\ControllerTrait;
use Yii;
use yii\web\Controller;

/**
 * TODO: delete after MVP
 */
class MobileAppController extends Controller
{
	use ControllerTrait;

	public function behaviors():array {
		return [
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}
	/**
	 * TODO: delete after MVP
	 */
	public function actionIndex()
	{
		$this->layout = 'empty';
		return $this->render('mvp');
	}

	/**
	 * TODO: delete after MVP
	 */
	public function actionConnect(int $id)
	{
		$abonent = Abonents::findOne(['phone' => '+79999897749']);
		if (null === $abonent) {
			$abonent = new Abonents(['phone' => '+79999897749', 'surname' => 'Лапин', 'name' => 'Алексей', 'patronymic' => 'Сергеевич']);
			$abonent->save();
		}
		$ticket = (new ProductTicketsService())->createSubscribeTicket($id, $abonent->id);

		Yii::$app->cache->set('mvp', [
			'<i class="fas fa-fw fa-check text-success"></i> В систему заведен абонент с номером 79999897749',
			'<i class="fas fa-fw fa-check text-success"></i> Создан тикет на подключение подписки: ' . $ticket
		]);
	}

	/**
	 * TODO: delete after MVP
	 */
	public function actionDisconnect(int $id)
	{
		$abonent = Abonents::findOne(['phone' => '+79999897749']);
		$ticket = (new ProductTicketsService())->createUnsubscribeTicket($id, $abonent->id);

		Yii::$app->cache->set('mvp', ['<i class="fas fa-fw fa-check text-success"></i> Создан тикет на отключение подписки: ' . $ticket]);
	}

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath(): string
	{
		return '@app/views/mobile-app';
	}
}