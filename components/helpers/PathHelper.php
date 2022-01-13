<?php
declare(strict_types = 1);

namespace app\components\helpers;

use pozitronik\helpers\PathHelper as VendorPathHelper;
use Yii;
use yii\base\Exception;

/**
 * Class PathHelper
 */
class PathHelper extends VendorPathHelper {

	/**
     * Возвращает случайное имя файла во временном каталоге с заданным префиксом и расширением
     * @param string|null $prefix Префикс имени файла
     * @param string|null $ext Расширение файла (без точки). Если не указано, будет использовано расширение 'tmp'
     * @return string
     * @throws Exception
     */
	public static function GetTempFileName(?string $prefix = null, ?string $ext = null):string {
		return sys_get_temp_dir().DIRECTORY_SEPARATOR.($prefix??'').Yii::$app->security->generateRandomString(6).'.'.$ext??'tmp';
	}

}