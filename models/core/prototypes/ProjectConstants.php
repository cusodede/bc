<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;
/**
 * Class ProjectConstants
 */
final class ProjectConstants {
	public const GENDER = [
		0 => 'Мужской',
		1 => 'Женский'
	];

	public const NON_RESIDENT_TYPE = [
		0 => 'Вид на жительство',
		1 => ' Виза',
		2 => 'Временное пребывание'
	];
}