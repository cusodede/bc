<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use pozitronik\helpers\Utils;
use Throwable;
use yii\base\Action;
use yii\web\Response;

/**
 * Class ProcessImportAction
 */
class ProcessImportAction extends Action {
	public array $mappingRules = [];

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function run(int $domain, string $modelClass) {
		$importModel = new ImportModel([
			'model' => $modelClass,
			'domain' => $domain,
			'mappingRules' => $this->mappingRules
		]);
		$messages = [];
		$isImportDone = $importModel->import($messages);
		if ([] !== $messages) { //на итерации найдены ошибки
			return $this->controller->render('@app/modules/import/views/import-errors', [
				'messages' => $messages,
				'domain' => $this->domain
			]);
		}

		if ($isImportDone) {
			$importModel->clear();
			return $this->controller->render('@app/modules/import/views/import-done', [
				'controller' => $this
			]);
		}
		return $this->controller->redirect([
			$this->id,
			'modelClass' => $modelClass,
			'domain' => $importModel->domain,
			'uuid' => Utils::gen_uuid()]);
	}
}