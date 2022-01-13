<?php
declare(strict_types = 1);

namespace app\components\db;

use app\components\Options;
use app\components\helpers\TemporaryHelper;
use app\models\sys\permissions\traits\ActiveQueryPermissionsTrait;
use pozitronik\dbmon\models\SqlDebugInfo;
use pozitronik\helpers\ArrayHelper;
use pozitronik\helpers\DateHelper;
use pozitronik\traits\models\ActiveQuery as VendorActiveQuery;
use Throwable;
use pozitronik\traits\traits\ActiveQueryTrait;
use Yii;

/**
 * Trait ActiveQueryTrait
 * Каст расширения запросов
 */
class ActiveQuery extends VendorActiveQuery {
	use ActiveQueryPermissionsTrait;
	use ActiveQueryTrait;

	/**
	 * При включении соответствующей опции добавляет отладочную информация в запросе
	 * @inheritDoc
	 */
	public function prepare($builder) {
		$activeQuery = parent::prepare($builder);
		return Options::getValue(Options::ENABLE_SQL_DEBUG_TRACE)
			?SqlDebugInfo::addDebugInfo($activeQuery, TemporaryHelper::DebugTrace(), Yii::$app->user->id??null)
			:$activeQuery;
	}

	/**
	 * Фильтровать выборку по попаданию между двумя датами, переданными одной строкой (из виджета DateRangePicker, например).
	 * @param string[]|string $field string: поле, значение которого проверяем на попадание в период, array: поля, содержащие даты начала и конца периода
	 * @param ?string $date Строковое представление периода
	 * @param ?string $datesDelimiter Разделитель дат в строке периода. Если null, считаем, что дата передана одним днём
	 * @param string $format php-формат дат в строке периода
	 * @return self
	 * @throws Throwable
	 *
	 * todo: обязательно написать тесты
	 */
	public function andFilterDateBetween(string|array $field, ?string $date, ?string $datesDelimiter = ' - ', string $format = 'Y-m-d'):ActiveQuery {
		if (null !== $date) {
			if (null === $datesDelimiter) {
				$beginDate = $date;
				$endDate = $beginDate;
			} else {
				if (2 !== count($dates = explode($datesDelimiter, $date))) return $this;//разделитель не найден
				$beginDate = ArrayHelper::getValue($dates, 0);
				$endDate = ArrayHelper::getValue($dates, 1);
			}
			if (DateHelper::isValidDate($beginDate, $format) && DateHelper::isValidDate($endDate, $format)) {/*Проверяем даты на валидность*/
				$beginDate .= ' 00:00:00';
				$endDate .= ' 23:59:59';
				(is_array($field))
					?$this->andWhere(['or', ['<=', $field[0], $beginDate], [$field[0] => null]])->andWhere(['or', ['>=', $field[1], $endDate], [$field[1] => null]])
					:$this->andFilterWhere(['between', $field, $beginDate, $endDate]);
			}
		}

		return $this;
	}

}