<?php
declare(strict_types = 1);

namespace app\components\console;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * class WithColorConsole
 */
trait WithColorConsole {
	/**
	 * @param string $message
	 * @param string $type
	 * @return string
	 */
	protected function colorizeMessage(string $message, string $type = 'info'):string {
		/**
		 * @var Controller $this
		 */
		switch ($type) {
			case 'info':
				$color = Console::FG_GREY;
			break;
			case 'warning':
				$color = Console::FG_YELLOW;
			break;
			case 'error':
				$color = Console::FG_RED;
			break;
			case 'success':
				$color = Console::FG_GREEN;
			break;
			default:
				$color = Console::FG_GREY;
		}

		return Console::ansiFormat($message, [$color]);
	}

	/**
	 * @param string $message
	 * @return string
	 */
	protected function withDate(string $message):string {
		return date('Y-m-d H:i:s').' '.$message;
	}

	/**
	 * @param string $message
	 */
	public function log(string $message):void {
		echo $this->colorizeMessage($this->withDate($message)).PHP_EOL;
	}

	/**
	 * @param string $message
	 */
	public function warning(string $message):void {
		echo $this->colorizeMessage($this->withDate($message), 'warning').PHP_EOL;
	}

	/**
	 * @param string $message
	 */
	public function error(string $message):void {
		echo $this->colorizeMessage($this->withDate($message), 'error').PHP_EOL;
	}

	/**
	 * @param string $message
	 */
	public function success(string $message):void {
		echo $this->colorizeMessage($this->withDate($message), 'success').PHP_EOL;
	}

	/**
	 * @return void
	 */
	public function memoryUsage():void {
		echo $this->colorizeMessage(
				$this->withDate(
					"memory usage allocated: ".
					(memory_get_usage(true) / 1024 / 1024).
					" Mb"
				)
			).PHP_EOL;
	}

	/**
	 * @return void
	 */
	public function memoryPeakUsage():void {
		echo $this->colorizeMessage(
				$this->withDate(
					"memory peak usage allocated: ".
					(memory_get_peak_usage(true) / 1024 / 1024).
					" Mb"
				)
			).PHP_EOL;
	}
}