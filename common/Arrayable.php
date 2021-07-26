<?php
declare(strict_types = 1);

namespace app\common;

/**
 * Interface Arrayable
 * @package app\common
 */
interface Arrayable
{
	/**
	 * @return array
	 */
	public function toArray(): array;
}