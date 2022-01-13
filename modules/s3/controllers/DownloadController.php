<?php
declare(strict_types = 1);

namespace app\modules\s3\controllers;

use app\components\helpers\PathHelper;
use app\modules\s3\models\cloud_storage\CloudStorage;
use app\modules\s3\models\S3;
use Aws\S3\Exception\S3Exception;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DownloadController
 */
class DownloadController extends Controller {

	/**
	 * @param int $id
	 * @return Response
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public function actionIndex(int $id):Response {
		$model = CloudStorage::findOne($id);
		if (null !== $model) {
			$savePath = PathHelper::GetTempFileName('pilot_', 'xls');
			try {
				(new S3())->getObject($savePath, $model->key, $model->bucket);
			} catch (S3Exception $e) {
				throw new NotFoundHttpException("Error in storage: {$e->getMessage()}");
			}

			if (file_exists($savePath)) {
				return Yii::$app->response->sendFile($savePath, $model->filename);
			}
			throw new NotFoundHttpException("File is not found!");
		}
		throw new NotFoundHttpException("Model is not found!");
	}

}
