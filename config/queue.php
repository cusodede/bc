<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

use yii\queue\file\Queue;

return [
	'common' => [ // общая очередь (импорты и т.д.)
		'class' => Queue::class,
		'path' => '@runtime/queues/common',
		'ttr' => 3600 /*часик в радость*/
	]
];
