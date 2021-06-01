<?php
declare(strict_types = 1);

use app\models\prototypes\merch\MerchOrder;
use app\models\seller\Sellers;
use app\models\sys\users\Users;

const CREATED = 1;//создан
const SENT = 2;//отправлен
const RECEIVED = 3;//получен
const DENIED = 4;//отказ поставки
const CANCELLED = 5;//отмена заказа отправителем
const DONE = 6;//полностью обработано
const ARCHIVE = 7;//в архиве

const SELLER_NOT_ACTIVE = 1;
const SELLER_ACTIVE = 2;
const SELLER_LIMITED = 3;
const SELLER_BLOCKED = 4;
const SELLER_SUSPENDED = 5;

/*
 * todo: Мультиобработчик для применения статусов
*/
return [
	Sellers::class => [
		SELLER_NOT_ACTIVE => [
			'id' => SELLER_NOT_ACTIVE,
			'name' => 'Не активирован',
			'initial' => true,
			'finishing' => false,
			'next' => [SELLER_ACTIVE, SELLER_LIMITED, SELLER_BLOCKED, SELLER_SUSPENDED],
			'allowed' => false
		],
		SELLER_ACTIVE => [
			'id' => SELLER_ACTIVE,
			'name' => 'Активирован',
			'initial' => false,
			'finishing' => true,
			'next' => [SELLER_NOT_ACTIVE, SELLER_LIMITED, SELLER_BLOCKED, SELLER_SUSPENDED],
			'allowed' => static function(Sellers $model, Users $user):bool {
				return true;
			},
			'style' => 'background: #ffa700; color:black'//стили можно задавать напрямую
		],
		SELLER_LIMITED => [
			'id' => SELLER_LIMITED,
			'name' => 'Ограничен',
			'initial' => false,
			'finishing' => true,
			'next' => [SELLER_NOT_ACTIVE, SELLER_ACTIVE, SELLER_BLOCKED, SELLER_SUSPENDED],
			'allowed' => static function(Sellers $model, Users $user):bool {
				return true;
			},
			'color' => '#00ff00'
		],
		SELLER_BLOCKED => [
			'id' => SELLER_BLOCKED,
			'name' => 'Заблокирован',
			'initial' => false,
			'finishing' => true,
			'next' => [SELLER_NOT_ACTIVE, SELLER_ACTIVE, SELLER_LIMITED, SELLER_SUSPENDED],
			'allowed' => static function(Sellers $model, Users $user):bool {
				return true;
			},
			'color' => '#00ff00'
		],
		SELLER_SUSPENDED => [
			'id' => SELLER_SUSPENDED,
			'name' => 'Suspend',
			'initial' => false,
			'finishing' => true,
			'next' => [SELLER_NOT_ACTIVE, SELLER_ACTIVE, SELLER_LIMITED, SELLER_BLOCKED],
			'allowed' => static function(Sellers $model, Users $user):bool {
				return true;
			},
			'color' => '#00ff00'
		]
	],
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
			'allowed' => static function(MerchOrder $model, Users $user):bool {
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