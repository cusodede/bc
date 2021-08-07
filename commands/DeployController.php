<?php
declare(strict_types = 1);

namespace app\commands;

use app\models\migration\Migration;
use Yii;
use yii\console\Controller;

/**
 * Class DeployController
 * @package app\commands
 */
class DeployController extends Controller {
	protected const COUNT_NEW_MIGRATIONS_BEFORE_DEPLOY = 'count_new_migrations_before_deploy';
	protected const COUNT_MIGRATIONS_IN_HISTORY_BEFORE_DEPLOY = 'count_migrations_in_history_before_deploy';

	/**
	 * Сохранить кол-во новых миграций до деплоя
	 */
	public function actionRememberCountMigrationsBeforeDeploy():void {
		Yii::$app->cache->set(
			self::COUNT_MIGRATIONS_IN_HISTORY_BEFORE_DEPLOY,
			$allCount = Migration::find()->count(),
			60 * 60 * 24 * 2
		);
		echo "Count migrations before deploy:".$allCount.PHP_EOL;
	}

	/**
	 * Вывести кол-во миграций для отката
	 */
	public function actionDiffCountMigrationsAfterDeploy():void {
		$countAllBefore = (int)Yii::$app->cache->get(self::COUNT_MIGRATIONS_IN_HISTORY_BEFORE_DEPLOY);
		$countAllAfter = Migration::find()->count();
		$diff = $countAllAfter - $countAllBefore;
		if ($diff > 0) {
			echo "COUNT_MIGRATIONS_FOR_ROLLBACK=$diff".PHP_EOL;
		}
	}
}
