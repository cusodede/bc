<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\users\Users;
use pozitronik\sys_options\models\SysOptions;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class AjaxController
 */
class AjaxController extends Controller {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		parent::init();
		$this->enableCsrfValidation = false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				]
			]
		];
	}

	/**
	 * Аяксовый поиск пользователя в Select2
	 * @param string|null $term
	 * @return string[][]
	 */
	public function actionSearchUsers(?string $term):array {
		$out = ['results' => ['id' => '', 'text' => '']];
		if (null !== $term) {
			$tableName = Users::tableName();
			$data = Users::find()
				->select(["{$tableName}.id", "{$tableName}.username as text"])
				->where(['like', "{$tableName}.username", "%$term%", false])
				->active()
				->distinct()
				->asArray()
				->all();
			$out['results'] = array_values($data);
		}
		return $out;
	}

	/**
	 * Применение настроек
	 * @return array
	 */
	public function actionSetSystemOption():array {
		$valueType = Yii::$app->request->post('type', 'string');
		switch ($valueType) {//JS types
			default:
			case 'string':
				$value = (string)Yii::$app->request->post('value');
			break;
			case 'boolean':
				$value = 'true' === Yii::$app->request->post('value');
			break;
			case 'number':
				$value = (int)Yii::$app->request->post('value');
			break;
		}

		return [
			'success' => SysOptions::setStatic(Yii::$app->request->post('name'), $value)
		];
	}

}
