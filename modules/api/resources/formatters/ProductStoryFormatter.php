<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\controllers\ProductsController;
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
				'storyLogoPath' => static function (Products $product) {
					return (null !== $product->fileStoryLogo)
						? ProductsController::to('get-story-logo', ['id' => $product->id])
						: null;
				}
			]
		]);
	}
}