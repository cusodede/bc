<?php
declare(strict_types = 1);

namespace app\modules\graphql\definition;

use DateTime;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use DateTimeInterface;
use GraphQL\Error\InvariantViolation;
use GraphQL\Utils\Utils;

/**
 * Class DateTimeType
 * Реализация типа DateTime для GraphQL.
 * @package app\modules\graphql\definition
 */
class DateTimeType extends ScalarType
{
	/**
	 * @var string
	 */
	public $name = 'DateTime';

	/**
	 * @var string
	 */
	public $description = 'Тип DateTime для GraphQL';

	/**
	 * @var ScalarType|null
	 */
	private static ?ScalarType $dateTimeType;

	private const DEFAULT_FORMAT = 'Y-m-d H:i:s';

	/**
	 * Сериализация входящих значений.
	 * @param mixed $value
	 * @return string
	 */
	public function serialize($value): string
	{
		if (!$value instanceof DateTimeInterface) {
			throw new InvariantViolation(
				'Входящие значение не соответствует DateTimeInterface: '
				. Utils::printSafe($value)
			);
		}

		return $value->format(self::DEFAULT_FORMAT);
	}

	/**
	 * Преобразует входящее значение в формат по умолчанию.
	 * @param mixed $value
	 * @return DateTime|null
	 */
	public function parseValue($value): ?DateTime
	{
		return DateTime::createFromFormat(self::DEFAULT_FORMAT, $value) ?: null;
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
	public static function dateTime(): ScalarType
	{
		return static::$dateTimeType ?? (static::$dateTimeType = new DateTimeType());
	}
}