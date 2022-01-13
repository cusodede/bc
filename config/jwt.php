<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

use cusodede\jwt\Jwt;

return [
	'class' => Jwt::class,
	'signer' => Jwt::RS256,
	'signerKey' => getenv('JWT_KEY_PATH'),
	'verifyKey' => getenv('JWT_KEY_PUB_PATH'),
];