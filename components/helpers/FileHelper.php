<?php
declare(strict_types = 1);

namespace app\components\helpers;

use finfo;
use RuntimeException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper as YiiFileHelper;
use yii\web\UploadedFile;

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
	public static function mimedBase64(string $filename): string
	{
		$content = base64_encode(file_get_contents($filename));

		return (null === $mimeType = self::getMimeType($filename)) ? $content : "data:{$mimeType};base64,{$content}";
	}

	/**
	 * Создание временного файла из raw контента.
	 * @param string $data
	 * @return string
	 */
	public static function createTmpFromRaw(string $data): string
	{
		$ext = self::getExtensionsByMimeType(self::getRawMimeType($data));
		if ([] !== $ext) {
			//т.к. mime тип может иметь несколько соответствий с доступными расширениями,
			//имеет смысл предусмотреть приоритизацию расширений.
			$ext = $ext[0];
		}

		$name = uniqid((string) mt_rand(), true);
		$path = self::getTmpDir() . DIRECTORY_SEPARATOR . $name . ($ext ? ".$ext" : '');

		if ($path && ($fp = fopen($path, 'wb+')) && fwrite($fp, $data) && fclose($fp)) {
			return $path;
		}

		throw new RuntimeException("Can't access temp file $path!");
	}

	/**
	 * Определение mime type для raw контента.
	 * @param string $str
	 * @return string
	 */
	public static function getRawMimeType(string $str): string
	{
		return (new finfo(FILEINFO_MIME_TYPE))->buffer($str);
	}

	/**
	 * @param string $path
	 * @return UploadedFile
	 * @throws InvalidConfigException
	 */
	public static function createUploadedFileInstance(string $path): UploadedFile
	{
		return new UploadedFile([
			'name'     => basename($path),
			'type'     => self::getMimeType($path, null, false),
			'tempName' => $path,
			'error'    => UPLOAD_ERR_OK
		]);
	}

	/**
	 * @return string
	 */
	public static function getTmpDir(): string
	{
		return sys_get_temp_dir();
	}
}