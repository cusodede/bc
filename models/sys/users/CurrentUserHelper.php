<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\web\Response;

/**
 * Class CurrentUserHelper
 */
class CurrentUserHelper {

	/**
	 * Отправляет на домашнюю страницу
	 * @return Response
	 * @throws Throwable
	 */
	public static function goHome():Response {
		return Yii::$app->response->redirect(self::homeUrl());
	}

	/**
	 * @return array
	 * @throws Throwable
	 */
	public static function homeUrl():array {
		return (array)ArrayHelper::getValue(Yii::$app->params, 'user.homepage', ['home/home']);
	}

	/**
	 * @return int|null
	 */
	public static function Id():?int {
		return Yii::$app->user->id;
	}

	/**
	 * @return bool
	 */
	public static function isGuest():bool {
		return Yii::$app->user->isGuest;// || null === Yii::$app->user->id;
	}

	/**
	 * @return Users
	 */
	public static function model():Users {
		$id = self::Id();
		return Yii::$app->cache->getOrSet(static::class."::model{$id}", static function() use ($id) {
			return Users::findOne($id);
		});
	}

}