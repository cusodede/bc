<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use app\modules\graphql\schema\types\common\OrderType;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use Throwable;
use yii\base\InvalidValueException;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\data\Pagination;

/**
 * Class ResolveParameters
 * @property mixed $root
 * @property array $args Массив аргументов запроса
 * @property mixed $context
 * @property null|ResolveInfo $resolveInfo Structure containing information useful for field resolution process.
 * @property DataProviderInterface $dataProvider DataProvider, используемый для работы с выборкой
 *
 * @property-read array $paginationInfo
 * @property-read array $orderInfo
 * @property-read bool $hasPagination Был ли в запросе параметр пагинации
 * @property-read int $limit
 * @property-read int $offset
 * @property-read Pagination $pagination
 *
 * @property-read array $items Набор полученных записей. Или можно просто вызывать класс, как функцию @see ResolveParameter::__invoke()
 */
class ResolveParameter extends Model {
	public mixed $root = null;
	public array $args = [];
	public mixed $context = null;
	public ?ResolveInfo $resolveInfo;

	private ?bool $_hasPagination = null;
	private ?DataProviderInterface $_dataProvider = null;

	/**
	 * Немножко магии: при обращении к классу, как к функции, возвращаем содержащийся в нём набор моделей
	 * @return array
	 */
	public function __invoke():array {
		return $this->items;
	}

	/**
	 * @return array
	 */
	public function getItems():array {
		return $this->dataProvider->models;
	}

	/**
	 * @param string $filterName
	 * @param mixed|null $default
	 * @return mixed
	 * @throws Throwable
	 */
	public function filterValue(string $filterName, mixed $default = null):mixed {
		return BaseField::filterValue($this->args, $filterName, $default);
	}

	/**
	 * @param string $argumentName
	 * @return mixed
	 * @throws Throwable
	 */
	public function argument(string $argumentName):mixed {
		return ArrayHelper::getValue($this->args, $argumentName, new Error("Unknown argument {$argumentName}"));
	}

	/**
	 * @return array
	 */
	public function getPaginationInfo():array {
		return [
			'limit' => $this->limit,
			'offset' => $this->offset,
			'totalCount' => $this->dataProvider->getTotalCount(),
			'page' => $this->pagination->page,
			'pageSize' => $this->pagination->pageSize,
			'pageCount' => $this->pagination->pageCount
		];
	}

	/**
	 * @return array
	 * @throws Throwable
	 */
	public function getOrderInfo():array {
		if ([] === $attributeOrders = $this->dataProvider->getSort()->attributeOrders) return [];
		$field = ArrayHelper::key($attributeOrders);
		return [
			'field' => $field,
			'ascending' => SORT_ASC === $attributeOrders[$field],//true for ascending sort
			'directionString' => SORT_ASC === $attributeOrders[$field]?OrderType::DIRECTION_STRING_ASC:OrderType::DIRECTION_STRING_DESC
		];
	}

	/**
	 * @return bool
	 */
	public function getHasPagination():bool {
		if (null === $this->_hasPagination) {
			$this->_hasPagination = array_key_exists(BasePaginatedObjectType::PAGINATION_FIELD_NAME, $this->args);
		}
		return $this->_hasPagination;
	}

	/**
	 * Генерирует из параметров запроса набор параметров для поисковой модели
	 * @param string $formName
	 * @return array
	 * @throws Throwable
	 */
	public function searchData(string $formName):array {
		return [
			$formName => ArrayHelper::getValue($this->args, 'filters', [])
		];
	}

	/**
	 * @return DataProviderInterface
	 */
	public function getDataProvider():DataProviderInterface {
		if (null === $this->_dataProvider) {
			throw new InvalidValueException('DataProvider is not initialized');
		}
		return $this->_dataProvider;
	}

	/**
	 * @param DataProviderInterface $dataProvider
	 * @throws Throwable
	 */
	public function setDataProvider(DataProviderInterface $dataProvider):void {
		$this->_dataProvider = $dataProvider;
		$this->pagination->pageSize = BasePaginatedField::pageSize($this->args);
		$this->pagination->page = BasePaginatedField::page($this->args);
		$this->_dataProvider->getSort()->setAttributeOrders($this->attributeOrders());
	}

	/**
	 * Формирует порядок сортировки для dataProvider
	 * @return bool[]|null
	 * @throws Throwable
	 */
	private function attributeOrders():?array {
		if ((null === $order = ArrayHelper::getValue($this->args, BasePaginatedObjectType::ORDER_FIELD_NAME)) || null === $field = $order['field']??null) return null;
		return [
			$field => ($order['ascending']??true)?SORT_ASC:SORT_DESC
		];
	}

	/**
	 * @return int
	 */
	public function getLimit():int {
		return $this->pagination->limit;
	}

	/**
	 * @return int
	 */
	public function getOffset():int {
		return $this->pagination->offset;
	}

	/**
	 * @return Pagination
	 */
	public function getPagination():Pagination {
		return $this->dataProvider->getPagination();
	}

}