<?php
declare(strict_types = 1);

namespace app\commands;

use app\models\core\prototypes\PHPDocParser;
use app\models\core\prototypes\SwaggerConverter;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class SwaggerController
 * @package app\commands
 */
class SwaggerController extends Controller {

	/**
	 * Инициализирует приложение
	 * @return void
	 */
	public function actionModel(string $path):void {
		$realPath = Yii::getAlias($path);
		if (false === $fileContents = file($realPath)) {
			Console::output(Console::renderColoredString("%rФайл {$realPath} не найден.%n"));
		} else {
			$parsers = [];
			$properties = PHPDocParser::ExtractAttributeLines($fileContents);
			foreach ($properties as $propertyLine) {
				$parser = new PHPDocParser();
				$parser->loadString($propertyLine);
				$parsers[] = $parser;
				Console::output(Console::renderColoredString("%b{$parser}%n"));
			}
			$converter = new SwaggerConverter(['parsers' => $parsers]);

			Console::output(Console::renderColoredString("%g{$converter}%n"));
		}
	}
}