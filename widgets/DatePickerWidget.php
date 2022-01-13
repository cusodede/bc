<?php
declare(strict_types = 1);

namespace app\widgets;

use kartik\date\DatePicker;

/**
 * Виджет DatePicker с общим набором настроек для проекта
 * @package app\widgets
 */
class DatePickerWidget extends DatePicker {
	/**
	 * @inheritDoc
	 */
	public function init():void {
		parent::init();

		$this->pluginOptions = array_merge(
			$this->pluginOptions,
			[
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'endDate' => date('Y-m-d'),
				'autoclose' => true,
			]
		);
	}
}
