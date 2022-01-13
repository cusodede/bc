<?php
declare(strict_types = 1);

namespace app\components\helpers;

use app\components\Options;
use app\models\sys\users\Users;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap4\Html as Bs4Html;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Html-хелпер с помогайками для приложения
 */
class Html extends Bs4Html {
	public const CONFIG_OPTION = null;
	public const YES = true;
	public const NO = false;

	/**
	 * Аналог Html::a(), с проверкой прав доступа к ссылке и возможностью переключения режима вызова ссылки (переход/загрузка в модалку).
	 * @param string $text
	 * @param string|array $url
	 * @param array $options Дополнительно можно использовать ключи:
	 *        fallback-url - адрес, который попытается использовать генератор при отсутствии доступа к изначальному url.
	 *            Доступность fallback-url тоже будет проверена.
	 *        modal-id - идентификатор модального окна, которое должно быть показано. Если не указан, js-обработчик
	 *            попытается найти ближайшую подходящую модалку самостоятельно.
	 * @param bool|null $useAjaxModal Генерация ссылки: true - с модалкой по дефолту, false - as is, null - по настройке из глобальной конфигурации
	 * @param mixed|null $default Значение, возвращаемое в случае, если fallback-url не определён. Если установлено в null
	 *        то метод вернёт значение $text as is
	 * @return mixed
	 * @throws ForbiddenHttpException
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function link(string $text, string|array $url, array $options = [], ?bool $useAjaxModal = self::CONFIG_OPTION, mixed $default = ''):mixed {
		/**
		 * Это post-ссылка, обслуживаемая обработчиками Yii
		 */
		if ([] !== array_intersect(['data-method', 'data-toggle', 'data-target'], array_keys($options))) return parent::a($text, $url, $options);

		$fallbackUrl = ArrayHelper::remove($options, 'fallback-url');
		$default = $default??$text;
		$url = Url::to($url);
		/**
		 * Для неавторизованного пользователя делаем фаллбек на стандартный метод. Даже если он кликнет каким-то образом
		 * по недоступной ссылке, его отобьёт фильтрами.
		 */
		if (Yii::$app->user->isGuest) return parent::a($text, $url, $options);
		if (Users::Current()->hasUrlPermission($url)) {
			$useAjaxModal = $useAjaxModal??Options::getValue(Options::AJAX_MODALS_ENABLED);
			if (self::YES === $useAjaxModal) {
				$options = array_merge_recursive($options, [
					'data' => [
						'ajax-url' => $url,
						'modal-id' => $options['modal-id']??null//если указан
					],
					'class' => ['el-ajax-modal']//На .el-ajax-modal навешивается обработчик в modalHelper.js
				]);
			}
			return parent::a($text, $url, $options);
		}
		return (null === $fallbackUrl)?$default:self::link($text, $fallbackUrl, $options, $useAjaxModal);
	}

}