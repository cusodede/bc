<?php
declare(strict_types = 1);

namespace app\components;

use kartik\markdown\MarkdownEditor;
use pozitronik\helpers\ArrayHelper;

/**
 * Класс для создания пресетов и управления настройками маркдауна.
 * Class PresetMarkdownEditor
 * @package app\components
 */
class PresetMarkdownEditor
{

	public static array $presetDefault = [
		'showExport' => false,
		'footerMessage' => false,
		'toolbar' => [
			[
				'buttons' => [
					MarkdownEditor::BTN_BOLD => ['icon' => 'bold', 'title' => 'Полужирный'],
					MarkdownEditor::BTN_ITALIC => ['icon' => 'italic', 'title' => 'Курсив'],
					MarkdownEditor::BTN_LINK => ['icon' => 'link', 'title' => 'Ссылка'],
					MarkdownEditor::BTN_INDENT_L => ['icon' => 'indent', 'title' => 'Увеличить отступ'],
					MarkdownEditor::BTN_INDENT_R => ['icon' => 'outdent', 'title' => 'Уменьшить отступ'],
					MarkdownEditor::BTN_UL => ['icon' => 'list', 'title' => 'Маркированный список'],
					MarkdownEditor::BTN_OL => ['icon' => 'list-alt', 'title' => 'Нумерованный список'],
					MarkdownEditor::BTN_HR => ['icon' => 'minus', 'title' => 'Горизонтальная линия']
				]
			]
		]
	];

	/**
	 * Метод для использования настраиваемого пресета.
	 * @param array $setting Массив настроек должен передаваться вида ключ => значение, где в виде значения может быть массив.
	 * @return array
	 */
	public static function useCustomPreset(array $setting): array
	{
		return ArrayHelper::merge($setting, self::$presetDefault);
	}
}
