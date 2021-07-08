<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\components\helpers\FileHelper;
use app\models\products\Products;
use yii\helpers\ArrayHelper;

/**
 * Class ProductStoryFormatter
 * @package app\modules\api\resources\formatters
 */
class ProductStoryFormatter implements ProductFormatterInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function format(Products $product): array
	{
		return ArrayHelper::toArray($product, [
			Products::class => [
				'id',
				'name',
				'description',
				'storyLogo' => static function (Products $product) {
					return FileHelper::mimeBase64($product->fileStoryLogo->path);
				}
			]
		]);
	}
}