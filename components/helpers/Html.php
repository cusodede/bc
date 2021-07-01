<?php
declare(strict_types = 1);

namespace app\components\helpers;

/**
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
}