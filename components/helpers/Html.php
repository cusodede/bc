<?php
declare(strict_types = 1);

namespace app\components\helpers;

use pozitronik\helpers\ArrayHelper;
use yii\bootstrap4\Html as Bs4Html;

/**
 * Класс для генерации всяких стилистически преднастроенных элементов DOM.
 * Class Html
 * @package app\components\helpers
 */
class Html extends Bs4Html
{
	/**
	 * Генерация ссылки для подгрузки модалки через ModalHelperAsset.
	 * @param string $text
	 * @param string $url
	 * @param array $options
	 * @return string
	 */
	public static function ajaxModalLink(string $text, string $url, array $options = []): string
	{
		return parent::a($text, $url, ArrayHelper::merge($options, ['data' => ['ajax-url' => $url], 'class' => ['el-ajax-modal']]));
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
	public static function badgeError(string $text): string
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