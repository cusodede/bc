<?php
declare(strict_types = 1);

use app\models\prototypes\merch\MerchOrder;
use app\models\sys\users\Users;

const CREATED = 1;//создан
const SENT = 2;//отправлен
const RECEIVED = 3;//получен
const DENIED = 4;//отказ поставки
const CANCELLED = 5;//отмена заказа отправителем
const DONE = 6;//полностью обработано
const ARCHIVE = 7;//в архиве

/*
 * todo: Мультиобработчик для применения статусов
*/
return [
	MerchOrder::class => [
		CREATED => [
			'name' => 'Создан',
			'initial' => true,
			'finishing' => false,
			'next' => [SENT, ARCHIVE],
			'allowed' => false

		],
		SENT => [
			'name' => 'Отправлен',
			'initial' => false,
			'finishing' => false,
			'next' => [CANCELLED, RECEIVED, DENIED],
			'allowed' => static function(MerchOrder $model, Users $user):bool {
				return true;
			}
		],
		RECEIVED => [
			'name' => 'Получен',
			'initial' => false,
			'finishing' => false,
			'next' => [DONE],
			'allowed' => static function(MerchOrder $model, Users $user):bool {
				return true;
			},
			'color' => '#ff0000'
		],
		DENIED => [
			'name' => 'Отказано в поставке',
			'initial' => false,
			'finishing' => true,
			'next' => [ARCHIVE],
			'allowed' => static function(MerchOrder $model, Users $user):bool {
				return true;
			},
			'color' => '#ffff00',
			'textcolor' => 'black'
		],
		CANCELLED => [
			'name' => 'Отменен',
			'initial' => false,
			'finishing' => true,
			'next' => [ARCHIVE],
			'allowed' => static function(MerchOrder $model, Users $user):bool {
				return true;
			},
			'style' => 'background: #ffa700; color:black'//стили можно задавать напрямую
		],
		DONE => [
			'name' => 'Получен',
			'initial' => false,
			'finishing' => true,
			'next' => [ARCHIVE],
			'allowed' => static function(MerchOrder $model, Users $user):bool {/*Одобрить задание может только текущий кластерлид*/
				return true;
			},
			'color' => '#00ff00'
		],
		ARCHIVE => [
			'name' => 'В архиве',
			'initial' => false,
			'finishing' => false,
			'next' => [],
			'allowed' => static function(MerchOrder $model, Users $user):bool {
				return true;
			},
			'color' => '#d8e2e8',
			'textcolor' => 'black'
		]
	]
];