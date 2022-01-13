<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use pozitronik\sys_exceptions\models\SysExceptions;
use Throwable;
use Yii;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class ImportJob
 */
class ImportJob extends ImportModel implements JobInterface {

	/**
	 * @param Queue $queue
	 * @return void
	 * @throws Throwable
	 */
	public function execute($queue):void {
		ini_set('memory_limit', '512M');
		try {
			if ($this->preload()) {
				$this->import();
				$this->clear();
			}
		} catch (Throwable $throwable) {
			SysExceptions::log($throwable, true);
		}

	}

	/**
	 * @return bool
	 */
	public function register():bool {
		$this->updateFilename();
		return null !== Yii::$app->queue_common->push($this);
	}
}