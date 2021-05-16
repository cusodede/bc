<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\TemporaryHelper;
use app\models\sys\permissions\Permissions;
use app\models\sys\permissions\PermissionsCollections;
use app\models\sys\users\Users;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\sys_options\models\SysOptions;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class AjaxController
 */
class AjaxController extends Controller {
	use ControllerTrait;

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
	 * Аяксовый поиск пользователя в глобальной искалке
	 * @param string|null $term
	 * @param int $limit
	 * @return string[][]
	 */
	public function actionSearchUsers(?string $term, int $limit = 5):array {
		$tableName = Users::tableName();
		$t_term = TemporaryHelper::SwitchKeyboard($term);
		return Users::find()
			->select(["{$tableName}.id", "{$tableName}.username as name"])
			->orWhere(['like', "{$tableName}.username", "%$term%", false])
			->orWhere(['like', "{$tableName}.username", "%$t_term%", false])
			->active()
			->distinct()
			->limit($limit)
			->asArray()
			->all();
	}

	/**
	 * Аяксовый поиск доступа по имени в глобальной искалке
	 * @param string|null $term
	 * @param int $limit
	 * @return string[][]
	 */
	public function actionSearchPermissions(?string $term, int $limit = 5):array {
		$tableName = Permissions::tableName();
		$t_term = TemporaryHelper::SwitchKeyboard($term);
		return Permissions::find()
			->select(["{$tableName}.id", "{$tableName}.name as name", "{$tableName}.controller as controller"])
			->orWhere(['like', "{$tableName}.name", "%$term%", false])
			->orWhere(['like', "{$tableName}.controller", "%$term%", false])
			->orWhere(['like', "{$tableName}.name", "%$t_term%", false])
			->orWhere(['like', "{$tableName}.controller", "%$t_term%", false])
			->active()
			->distinct()
			->limit($limit)
			->asArray()
			->all();
	}

	/**
	 * Аяксовый поиск группы доступов по имени в глобальной искалке
	 * @param string|null $term
	 * @param int $limit
	 * @return string[][]
	 */
	public function actionSearchPermissionsCollections(?string $term, int $limit = 5):array {
		$tableName = PermissionsCollections::tableName();
		$t_term = TemporaryHelper::SwitchKeyboard($term);
		return PermissionsCollections::find()
			->select(["{$tableName}.id", "{$tableName}.name as name"])
			->orWhere(['like', "{$tableName}.name", "%$term%", false])
			->orWhere(['like', "{$tableName}.name", "%$t_term%", false])
			->active()
			->distinct()
			->limit($limit)
			->asArray()
			->all();
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
