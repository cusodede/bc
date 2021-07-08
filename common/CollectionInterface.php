<?php
declare(strict_types = 1);

namespace app\common;

/**
 * Interface CollectionInterface
 * @package app\components
 */
interface CollectionInterface
{
	/**
	 * Получение массива объектов в коллекции.
	 * @return array
	 */
	public function getItems(): array;

	/**
	 * Проверка коллекции на пустоту.
	 * @return bool
	 */
	public function isEmpty(): bool;
}