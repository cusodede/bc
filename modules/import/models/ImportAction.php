<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use Throwable;
use yii\base\Action;
use yii\web\Response;

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
	 * @return string|Response
	 * @throws Throwable
	 */
	public function run() {

		$importModel = new ImportModel([
			'model' => $this->modelClass,
			'skipRows' => $this->skipRows,
			'skipEmptyRows' => $this->skipEmptyRows
		]);

		if (([] !== $importModel->uploadAttribute('importFile')) && $importModel->preload()) {
			return $this->controller->redirect(['process-import', 'domain' => $importModel->domain]);
		}

		return $this->controller->render('@app/modules/import/views/import', [
			'controller' => get_class($this->controller),
			'model' => $importModel
		]);
	}
}