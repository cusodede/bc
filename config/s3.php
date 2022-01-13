<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
use app\modules\s3\S3Module;

if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

return [
	'class' => S3Module::class,
	'params' => [
		'connection' => [
			'host' => getenv('MINIO_HOST'),
			'login' => getenv('MINIO_ROOT_USER'),
			'password' => getenv('MINIO_ROOT_PASSWORD'),
			'connect_timeout' => getenv('MINIO_CONNECT_TIMEOUT'),
			'timeout' => getenv('MINIO_TIMEOUT'),
			'cert_path' => getenv('MINIO_CERT_PATH')
		],
		'defaultBucket' => null
	]
];