<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\sse\MessageEventHandler;
use app\models\sys\users\CurrentUserHelper;
use app\models\sys\users\Users;
use pozitronik\core\models\SqlDebugInfo;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\helpers\Utils;
use pozitronik\sys_exceptions\models\LoggedException;
use pozitronik\sys_exceptions\models\SysExceptions;
use ReflectionException;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\UnknownClassException;
use yii\web\Controller;

/**
 * Class TestController
 * Контроллер на потестировать какие-то штуки. Естественно, в репу он попадать не должен
 */
class TestController extends Controller {
	use ControllerTrait;

	/**
	 * @return string
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function actionIndex():string {
		$actions = self::GetControllerActions();
		return $this->render('index', [
			'actions' => $actions
		]);
	}

	/**
	 * @throws Throwable
	 * @noinspection OpAssignShortSyntaxInspection
	 * @noinspection PhpDivisionByZeroInspection
	 * @noinspection PhpUnusedLocalVariableInspection
	 */
	public function actionExceptionsTest():void {
		$i = 10;
		try {
			$i = $i / 0;
		} catch (Throwable $t) {
			SysExceptions::log($t);//just silently log exception
			SysExceptions::log(new RuntimeException("Someone tried divide to zero"), false, true);//silently log own exception and mark it as known error
			throw new LoggedException(new RuntimeException("It prohibited by mathematics"));//log own exception and throw it
		}
	}

	/**
	 * Тестирование добавления отладочной информации в запросы
	 */
	public function actionSqlDebug():void {
		$query = Users::find();

		SqlDebugInfo::addDebugInfo($query, 'debug-process');

		Utils::log($query->createCommand()->rawSql);
		Utils::log($query->all());
	}

	/**
	 *
	 */
	public function actionSse():void {
		/** @noinspection PhpUndefinedFieldInspection */
		$sse = Yii::$app->sse;
		$sse->addEventListener('message', new MessageEventHandler());
		$sse->start();
	}

	/**
	 * @return string
	 */
	public function actionSseClient():string {
		return $this->render('sse-client');
	}

	/**
	 * Тесты доступов
	 */
	public function actionPermissions():void {
		Utils::log(CurrentUserHelper::model()->allPermissions());
	}
}