<?php
declare(strict_types = 1);

namespace app\modules\s3\models;

use app\modules\s3\models\cloud_storage\CloudStorage;
use app\modules\s3\S3Module;
use Aws\S3\S3Client;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * @package app\modules\s3\models
 *
 * @property S3Client $client
 */
class S3 extends Model {
	public CloudStorage $storage;
	private string $host;
	private string $login;
	private string $password;
	private int $connectTimeout;
	private int $timeout;
	private string $certPath;
	private ?string $defaultBucket;

	public const WEB_LOG = 's3.web';
	public const CONSOLE_LOG = 's3.console';

	public const BUCKET_PREFIX = 'dpl-';

	/**
	 * @inheritDoc
	 */
	public function __construct() {
		$this->host = S3Module::param("connection.host");
		$this->login = S3Module::param("connection.login");
		$this->password = S3Module::param("connection.password");
		$this->connectTimeout = (int)S3Module::param("connection.connect_timeout", 10);
		$this->timeout = (int)S3Module::param("connection.timeout", 10);
		$this->certPath = S3Module::param("connection.cert_path");
		$this->defaultBucket = S3Module::param("defaultBucket");
		parent::__construct();
	}

	/**
	 * @return S3Client
	 */
	public function getClient():S3Client {
		return new S3Client([
			'version' => 'latest',
			'region' => '', // обязательный параметр. Из доки AWS: Specifies which AWS Region to send this request to.
			'endpoint' => $this->host,
			'use_path_style_endpoint' => true, // определяет вид URL. Если true, то http://minio:9002/test/, иначе http://test.minio:9002/
			'http' => $this->getHttp(),
			'credentials' => [
				'key' => $this->login,
				'secret' => $this->password
			]
		]);
	}

	/**
	 * @return array
	 */
	private function getHttp():array {
		$http = [
			'connect_timeout' => $this->connectTimeout,
			'timeout' => $this->timeout
		];

		if ('' !== $this->certPath) {
			$http[] = [$this->certPath, '']; // второй элемент пароль, не думаю что будем его использовать
		}

		return $http;
	}

	/**
	 * Если bucket не задан явно, то идем в конфиг и берем defaultBucket. Если нет defaultBucket то, берем первый bucket по алфавиту
	 * @param string|null $bucket
	 * @return string
	 * @throws Throwable
	 */
	public function getBucket(?string $bucket):string {
		if (!empty($bucket)) {
			return $bucket;
		}
		if (!empty($this->defaultBucket)) {
			return $this->defaultBucket;
		}
		$buckets = ArrayHelper::getValue($this->client->listBuckets()->toArray(), 'Buckets', []);
		$lastBucket = count($buckets) - 1;
		return ArrayHelper::getValue($buckets, $lastBucket.'.Name', new NotFoundHttpException("Bucket не найден"));
	}

	/**
	 * Сохраняем объект в хранилище
	 * @param string $filePath
	 * @param string|null $bucket
	 * @throws Exception
	 * @throws Throwable
	 */
	public function saveObject(string $filePath, ?string $bucket = null):void {
		$filename = basename($filePath);
		$key = implode('_', [$filename, Yii::$app->security->generateRandomString()]);
		$storageResponse = $this->client->putObject([
			'Bucket' => $this->getBucket($bucket),
			'Key' => $key,
			'Body' => fopen($filePath, 'rb')
		]);

		$this->storage = new CloudStorage([
			'bucket' => $bucket,
			'key' => $key,
			'filename' => $filename,
			'uploaded' => null !== ArrayHelper::getValue($storageResponse->toArray(), 'ObjectURL')
		]);
		$this->storage->save();
	}

	/**
	 * Получаем объект из хранилище
	 * @param string $savePath
	 * @param string $key
	 * @param string|null $bucket
	 * @throws Throwable
	 */
	public function getObject(string $savePath, string $key, ?string $bucket = null):void {
		$this->client->getObject([
			'Bucket' => $this->getBucket($bucket),
			'Key' => $key,
			'SaveAs' => $savePath
		]);
	}

	/**
	 * Получаем список buckets
	 * @return array[]
	 */
	public function getListBucketMap():array {
		$res = $this->client->listBuckets()->toArray();
		$buckets = [];
		foreach ($res['Buckets'] as $bucket) {
			$buckets[$bucket['Name']] = $bucket['Name'];
		}
		return $buckets;
	}

	/**
	 * Создаем bucket
	 * @param string $name
	 * @return bool
	 * @throws Throwable
	 */
	public function createBucket(string $name):bool {
		$res = $this->client->createBucket(['Bucket' => self::BUCKET_PREFIX.$name])->toArray();
		return null !== ArrayHelper::getValue($res, 'Location');
	}

}
