<?php
declare(strict_types = 1);

namespace app\widgets\badgewidget;

use app\components\helpers\Html;
use pozitronik\helpers\ArrayHelper;
use pozitronik\widgets\BadgeWidget as VendorBadgeWidget;
use Throwable;
use yii\base\Model;

/**
 * Class BadgeWidget
 * Перекрывает метод генерации урла в вендорском виджете, чтобы генерировать ссылки через проектный хелпер
 *
 * @property null|bool $useAjaxModal Генерация ссылки: true - с модалкой по дефолту, false - as is, null - по настройке из глобальной конфигурации
 */
class BadgeWidget extends VendorBadgeWidget {
	public ?bool $useAjaxModal = null;

	/**
	 * @param Model $item
	 * @param string $content
	 * @return string
	 * @throws Throwable
	 */
	protected function prepareUrl(Model $item, string $content):string {
		if (false === $this->urlScheme) return $content;
		$useAjaxModal = $this->useAjaxModal??Html::CONFIG_OPTION;
		if (is_string($this->urlScheme)) return Html::link($content, $this->urlScheme, [], $useAjaxModal, null);
		$arrayedParameters = [];
		$currentLinkScheme = $this->urlScheme;
		array_walk($currentLinkScheme, static function(&$value, $key) use ($item, &$arrayedParameters) {//подстановка в схему значений из модели
			if (is_array($value)) {//value passed as SomeParameter => [a, b, c,...] => convert to SomeParameter[1] => a, SomeParameter[2] => b, SomeParameter[3] => c
				foreach ($value as $index => $item) {
					$arrayedParameters["{$key}[{$index}]"] = $item;
				}
			} elseif ($item->hasProperty($value) && false !== $attributeValue = ArrayHelper::getValue($item, $value, false)) $value = $attributeValue;

		});
		if ([] !== $arrayedParameters) $currentLinkScheme = array_merge(...$arrayedParameters);//если в схеме были переданы значения массивом, включаем их разбор в схему
		return Html::link($content, $currentLinkScheme, [], $useAjaxModal, null);
	}
}