<?php
declare(strict_types = 1);

namespace app\components\helpers;

use yii\base\InvalidConfigException;
use yii\helpers\FileHelper as YiiFileHelper;

/**
 * Class FileHelper
 * @package app\components\helpers
 */
class FileHelper extends YiiFileHelper
{
	/**
	 * @param string $filename
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function mimeBase64(string $filename): string
	{
		$content = base64_encode(file_get_contents($filename));
		
		return (null === $mimeType = self::getMimeType($filename))
			? $content 
			: "data:{$mimeType};base64,{$content}";
	}
}