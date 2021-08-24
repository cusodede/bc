<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\definition;

use DateTimeImmutable;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use DateTimeInterface;
use GraphQL\Error\InvariantViolation;
use GraphQL\Utils\Utils;

/**
 * Class DateTimeType
 * Реализация типа DateTime для GraphQL.
 * Сделано на будущее, если ребята с фронта, захотят другой формат даты.
 * @package app\modules\graphql\definition
 */
class DateTimeType extends ScalarType
{
	/**
	 * @var string
	 */
	public string $name = 'DateTime';

	/**
	 * @var string|null
	 */
	public ?string $description = 'Скалярный тип DateTime представляет данные даты и времени, в виде строки в формате ' . self::DEFAULT_FORMAT;

	/**
	 * @var ScalarType|null
	 */
	private static ?ScalarType $dateTimeType;

	public const DEFAULT_FORMAT = 'Y-m-d H:i:s';

	/**
	 * Сериализация входящих значений.
	 * @param mixed $value
	 * @return string
	 */
	public function serialize($value): string
	{
		if (!($value instanceof DateTimeInterface)) {
			throw new InvariantViolation(
				'Входящие значение не соответствует DateTimeInterface: ' . Utils::printSafe($value)
			);
		}

		return $value->format(self::DEFAULT_FORMAT);
	}

	/**
	 * Преобразует входящее значение в формат по умолчанию.
	 * @param mixed $value
	 * @return string|null
	 */
	public function parseValue($value): ?string
	{
		$value = DateTimeImmutable::createFromFormat(self::DEFAULT_FORMAT, $value);
		return $value instanceof DateTimeImmutable ? $value->format(self::DEFAULT_FORMAT) : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parseLiteral($valueNode, ?array $variables = null): ?string
	{
		return $valueNode instanceof StringValueNode ? $valueNode->value : null;
	}

	/**
	 * @return ScalarType
	 */
	public static function type(): ScalarType
	{
		return static::$dateTimeType ?? (static::$dateTimeType = new DateTimeType());
	}

	/**
	 * Статичный метод для преобразования строк.
	 * @param string|null $value
	 * @return DateTimeImmutable|null
	 */
	public static function parseString(?string $value): ?DateTimeImmutable
	{
		return null !== $value ? DateTimeImmutable::createFromFormat(self::DEFAULT_FORMAT, $value) : null;
	}
}