<?php
declare(strict_types = 1);

namespace app\components\helpers;

/**
 * Класс для генерации всяких стилистически преднастроенных элементов DOM.
 * Class Html
 * @package app\components\helpers
 */
class Html extends \yii\bootstrap4\Html
{
	/**
	 * Генерация ссылки для подгрузки модалки через ModalHelperAsset.
	 * @param string $text
	 * @param string $url
	 * @return string
	 */
	public static function ajaxModalLink(string $text, string $url): string
	{
		return parent::a($text, $url, ['data' => ['ajax-url' => $url], 'class' => 'el-ajax-modal']);
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public static function badgeSuccess(string $text): string
	{
		return parent::tag('span', $text, ['class' => ['badge border border-success text-success']]);
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public static function badgeDanger(string $text): string
	{
		return parent::tag('span', $text, ['class' => ['badge border border-danger text-danger']]);
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public static function badgeInfo(string $text): string
	{
		return parent::tag('span', $text, ['class' => ['badge border border-info text-info']]);
	}
}