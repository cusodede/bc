<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use Throwable;
use yii\base\Action;

/**
 * Class ImportAction
 * @property string $modelClass класс ActiveRecord-модели, к которой подключается импорт
 * @property int $skipRows количество пропускаемых строк от начала
 * @property bool $skipEmptyRows пропускать ли пустые строки
 */
class ImportAction extends Action {
	public string $modelClass;
	public int $skipRows = 1;
	public bool $skipEmptyRows = true;

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		$importJob = new ImportJob([
			'model' => $this->modelClass,
			'skipRows' => $this->skipRows,
			'skipEmptyRows' => $this->skipEmptyRows,
		]);

		if (([] !== $importJob->uploadAttribute('importFile') && $importJob->register())) {//todo: просто сразу редиректить
			return $this->controller->render('@app/modules/import/views/preload-done', [
				'controller' => get_class($this->controller),
				'job' => $importJob
			]);
		}

		return $this->controller->render('@app/modules/import/views/import', [
			'controller' => get_class($this->controller),
			'model' => $importJob
		]);
	}
}