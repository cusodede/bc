<?php
declare(strict_types = 1);

namespace app\modules\s3\controllers;

use app\components\db\ActiveRecordTrait;
use app\components\web\DefaultController;
use app\modules\s3\forms\CreateBucketForm;
use app\modules\s3\models\cloud_storage\CloudStorage;
use app\modules\s3\models\cloud_storage\CloudStorageSearch;
use app\modules\s3\models\S3;
use app\modules\s3\S3Module;
use pozitronik\helpers\ArrayHelper;
use pozitronik\helpers\ControllerHelper;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class TestController
 */
class TestController extends DefaultController {

	protected const DEFAULT_TITLE = 'Облачное хранилище';
	public bool $enablePrototypeMenu = false;
	public ?string $modelClass = CloudStorage::class;
	public ?string $modelSearchClass = CloudStorageSearch::class;

	/** @inheritdoc */
	protected const ACTION_TITLES = [
		'create-bucket' => 'Добавить bucket'
	];

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/modules/s3/views/test';
	}

	/**
	 * @inheritdoc
	 */
	public function actionCreate() {
		/** @var CloudStorage $model */
		$model = $this->model;
		$s3 = new S3();
		if (ControllerHelper::IsAjaxValidationRequest()) {
			return $this->asJson($model->validateModelFromPost());
		}
		if (true === Yii::$app->request->isPost && true === $model->load(Yii::$app->request->post())) {
			$uploadedFile = UploadedFile::getInstances($model, 'file');
			$bucket = $s3->getBucket($model->bucket);
			$storageResponse = $s3->client->putObject([
				'Bucket' => $bucket,
				'Key' => $model->key,
				'Body' => fopen($uploadedFile[0]->tempName, 'rb')
			]);
			Yii::info(json_encode($storageResponse->toArray()), S3::WEB_LOG);
			$model->bucket = $bucket;
			$model->uploaded = null !== ArrayHelper::getValue($storageResponse->toArray(), 'ObjectURL');
			if ($model->save()) {
				return $this->redirect(S3Module::to('/test'));
			}
			/* Есть ошибки */
			if (Yii::$app->request->isAjax) {
				return $this->asJson($model->errors);
			}
		}
		/* Постинга не было */
		return (Yii::$app->request->isAjax)
			?$this->renderAjax('modal/create', ['model' => $model, 'buckets' => $s3->getListBucketMap()])
			:$this->render('create', ['model' => $model, 'buckets' => $s3->getListBucketMap()]);
	}

	/**
	 * @inheritdoc
	 */
	public function actionEdit(int $id) {
		/** @var CloudStorage $model */
		if (null === $model = $this->model::findOne($id)) throw new NotFoundHttpException();
		$s3 = new S3();

		/** @var ActiveRecordTrait $model */
		if (ControllerHelper::IsAjaxValidationRequest()) {
			return $this->asJson($model->validateModelFromPost());
		}
		if (true === Yii::$app->request->isPost) {
			$uploadedFile = UploadedFile::getInstances($model, 'file');
			$storageResponse = $s3->client->putObject([
				'Bucket' => $model->bucket,
				'Key' => $model->key,
				'Body' => fopen($uploadedFile[0]->tempName, 'rb')
			]);
			Yii::info(json_encode($storageResponse->toArray()), S3::WEB_LOG);

			$model->uploaded = null !== ArrayHelper::getValue($storageResponse->toArray(), 'ObjectURL');
			if ($model->save()) {
				return $this->redirect(S3Module::to('/test'));
			}
			/* Есть ошибки */
			if (Yii::$app->request->isAjax) {
				return $this->asJson($model->errors);
			}
		}
		/* Постинга не было */
		return (Yii::$app->request->isAjax)
			?$this->renderAjax('modal/edit', ['model' => $model, 'buckets' => $s3->getListBucketMap()])
			:$this->render('edit', ['model' => $model, 'buckets' => $s3->getListBucketMap()]);
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionCreateBucket():string {
		$createBucketForm = new CreateBucketForm();
		$isCreated = null;
		if (true === Yii::$app->request->isPost && true === $createBucketForm->load(Yii::$app->request->post()) && $createBucketForm->validate()) {
			$isCreated = (new S3())->createBucket($createBucketForm->name);
			if (true === $isCreated) {
				$createBucketForm = new CreateBucketForm();
			}
		}
		return $this->render('create-bucket', compact('createBucketForm', 'isCreated'));
	}

}
