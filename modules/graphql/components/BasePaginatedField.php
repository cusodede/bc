<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use app\modules\graphql\schema\types\common\inputs\OrderTypeInput;
use app\modules\graphql\schema\types\common\inputs\PaginationTypeInput;
use app\modules\graphql\schema\types\common\OrderType;
use app\modules\graphql\schema\types\common\PaginationType;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ResolveInfo;
use Throwable;

/**
 * Class BasePaginatedField
 *
 * Договорились с Даней, что для каждого метода, возвращающего список, будет два варианта:
 *    %name%List - без пагинации, возвращает выборку;
 *    %name%ListPaginated - с пагинацией, возвращает выборку + инфу о пагинации.
 *
 * Зачем делать очевидное и усложняющее дублирование - вопрос не ко мне. Я вижу, что это очень плохое решение,
 * но иду на встречу фронтам, надеясь, что потом мы это ещё раз обсудим. В качестве оправдания могу сказать, что
 * предыдущий вариант, который хотел Даня, был ещё хуже.
 *
 * Этот класс пытается разрулить создание таких дублирующихся методов. Если мы создаём наследуемое поле через
 * ::field(true), то класс:
 * 1. Обязательным порядком конвертирует тип данных запроса в BasePaginatedObjectType
 * 2. Обязательным порядком добавляет полю аргумент пагинации (при его отсутствии).
 * 3. Обязательным параметром добавляет в ответ данные о пагинации (если они отсутствуют)
 *
 * иначе поле ведёт себя аналогично обычному BaseField
 *
 */
abstract class BasePaginatedField extends BaseField {

	public const DEFAULT_PAGE_SIZE = 1000;//negative value to disable limit
	public const DEFAULT_PAGE = 0;
	public const UNKNOWN_MAX_OFFSET = -1;//неизвестный максимальный размер выборки
	public const UNKNOWN_PAGE_COUNT = -1;//неизвестное максимальное количество страниц

	/**
	 * @var string[] Массив подгруженных полей
	 */
	private static array $_fieldsMap = [];

	/**
	 * Перекрываем родительский метод, поскольку нам приходится учитывать версии запроса с пагинацией и без.
	 *
	 * @param bool $paginatedVariant Создать вариант поля с пагинацией, или без, @see __construct
	 * @return static
	 * @throws Throwable
	 */
	public static function field(bool $paginatedVariant = false):static {
		if ($paginatedVariant) {
			if (null === ArrayHelper::getValue(self::$_fieldsMap, static::class)) {
				self::$_fieldsMap[static::class] = new static($paginatedVariant);
			}
			return self::$_fieldsMap[static::class];
		}
		return parent::field();
	}

	/**
	 * @inheritDoc
	 * @param bool $paginatedVariant Создать вариант поля с пагинацией, или без. По сути, конструктор здесь становится
	 * фабрикой. Это не очень удачная архитектура, на которой мне пришлось (надеюсь, временно) остановиться, чтобы
	 * обеспечить быстрое согласование api-схемы с фронтами.
	 * @throws Throwable
	 */
	protected function __construct(array $config, bool $paginatedVariant = false) {
		if (!isset($config['description'])) $config['description'] = $config['name'];

		if ($paginatedVariant) {

			/*Формируем имя и описание*/
			$config['name'] .= 'Paginated';
			$config['description'] .= ' с пагинацией';

			/*Конвертируем списочный тип данных в BasePaginatedObjectType */
			if (($config['type']::class === ListOfType::class)) {
				$config['type'] = self::ListTypeToPaginatedObject($config['type'], $config['name']);
			}

			/*Добавляем в поле аргумент пагинации*/
			if (!isset($config['args'][BasePaginatedObjectType::PAGINATION_FIELD_NAME])) {
				$config['args'][BasePaginatedObjectType::PAGINATION_FIELD_NAME] = [
					'type' => PaginationTypeInput::type()
				];
			}

			/*Добавляем в поле аргумент сортировки*/
			if (!isset($config['args'][BasePaginatedObjectType::ORDER_FIELD_NAME])) {
				$config['args'][BasePaginatedObjectType::ORDER_FIELD_NAME] = [
					'type' => OrderTypeInput::type()
				];
			}

			/* Добавляем в ответ данные о пагинации*/
			if (!isset($config['resolve'])) {
				$config['resolve'] = function(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo) {
					if ($this->requireAuthentication && null === AuthHelper::getAuthUser()) throw new Error('Require authentication');
					$parameterInfo = new ResolveParameter(compact('root', 'args', 'context', 'resolveInfo'));
					$resolveResult = static::resolve($parameterInfo);
					if (!isset($resolveResult[BasePaginatedObjectType::EDGES_FIELD_NAME])) {
						$tmp[BasePaginatedObjectType::EDGES_FIELD_NAME] = $resolveResult;
						$resolveResult = $tmp;
					}
					if (!isset($resolveResult[BasePaginatedObjectType::PAGINATION_FIELD_NAME])) {
						$resolveResult[BasePaginatedObjectType::PAGINATION_FIELD_NAME] = $parameterInfo->paginationInfo;
					}
					if (!isset($resolveResult[BasePaginatedObjectType::ORDER_FIELD_NAME])) {
						$resolveResult[BasePaginatedObjectType::ORDER_FIELD_NAME] = $parameterInfo->orderInfo;
					}
					return $resolveResult;
				};
			}
		}

		parent::__construct($config);
	}

	/**
	 * @param ListOfType $forListOf
	 * @param string $name
	 * @return BasePaginatedObjectType
	 * @throws Throwable
	 */
	private static function ListTypeToPaginatedObject(ListOfType $forListOf, string $name):BasePaginatedObjectType {
		$config = [
			'name' => $name,
			'fields' => [
				BasePaginatedObjectType::EDGES_FIELD_NAME => $forListOf,
				BasePaginatedObjectType::PAGINATION_FIELD_NAME => PaginationType::type(),
				BasePaginatedObjectType::ORDER_FIELD_NAME => OrderType::type()
			]
		];
		return new class($config) extends BasePaginatedObjectType {
		};
	}

	/**
	 * @param $args
	 * @return int
	 * @throws Throwable
	 */
	public static function pageSize($args):int {
		return ArrayHelper::getValue($args, BasePaginatedObjectType::PAGINATION_FIELD_NAME.".pageSize", self::DEFAULT_PAGE_SIZE);
	}

	/**
	 * @param $args
	 * @return int
	 * @throws Throwable
	 */
	public static function page($args):int {
		return ArrayHelper::getValue($args, BasePaginatedObjectType::PAGINATION_FIELD_NAME.".page", self::DEFAULT_PAGE);
	}

}