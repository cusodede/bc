<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use app\modules\import\models\active_record\ImportStatus;
use pozitronik\helpers\Utils;
use Throwable;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ImportProgressAction
 */
class ImportStatusAction extends Action {
	public array $mappingRules = [];
	public bool $ignoreErrors = true;

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function run(int $domain, string $modelClass) {
		if (null === $importStatus = ImportStatus::findImportStatus($modelClass, $domain)) {
			throw new NotFoundHttpException('Задание загрузки не найдено');
		}

		if (Yii::$app->request->isAjax) {
			return $this->controller->asJson([
				'status' => $importStatus->statusLabel,
				'processed' => $importStatus->processed?Utils::pluralForm($importStatus->processed, ['строка', 'строки', 'строк']):'N/A',
				'imported' => $importStatus->imported?Utils::pluralForm($importStatus->imported, ['строка', 'строки', 'строк']):'N/A',
				'percent' => $importStatus->percent,
				'skipped' => $importStatus->skipped?Utils::pluralForm($importStatus->skipped, ['строка', 'строки', 'строк']):'N/A',
				'error' => $importStatus->error??'Нет',
				'done' => in_array($importStatus->status, [ImportModel::STATUS_DONE, ImportModel::STATUS_ERROR], true)
			]);
		}

		return $this->controller->render('@app/modules/import/views/import-status', [
			'model' => $importStatus,
			'controller' => get_class($this->controller)
		]);

	}
}