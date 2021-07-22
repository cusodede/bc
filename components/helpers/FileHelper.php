<?php
declare(strict_types = 1);

namespace app\components\helpers;

use app\components\RawUploadedFile;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper as YiiFileHelper;
use yii\web\UploadedFile;
use RuntimeException;
use finfo;

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
		preg_match('/base64,(.+)/', $data, $matches);
		if (isset($matches[1])) {
			$data = base64_decode(trim($matches[1]));
			if (false === $data) {
				throw new RuntimeException('Unable to decode base64 string');
			}
		}

		$extension = self::getExtensionsByMimeType(self::getRawMimeType($data));
		//т.к. mime тип может иметь несколько соответствий с доступными расширениями,
		//имеет смысл предусмотреть приоритизацию расширений.
		$extension = self::findPreferredExtension($extension);

		$name = uniqid((string) mt_rand(), true);
		$path = self::getTmpDir() . DIRECTORY_SEPARATOR . $name . ($extension ? ".$extension" : '');

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
		return new RawUploadedFile($path);
	}

	/**
	 * @return string
	 */
	public static function getTmpDir(): string
	{
		return sys_get_temp_dir();
	}

	/**
	 * Выбираем предпочтительное расширение для файла на основании массива расширений для mime type.
	 * @param array $extensions
	 * @return string|null
	 */
	private static function findPreferredExtension(array $extensions): ?string
	{
		$preferred = ['jpg', 'svg', 'txt'];
		foreach ($preferred as $ext) {
			if (in_array($ext, $extensions, true)) {
				return $ext;
			}
		}

		return array_shift($extensions);
	}
}