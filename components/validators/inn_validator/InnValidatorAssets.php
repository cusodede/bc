<?php
declare(strict_types = 1);

namespace app\components\validators\inn_validator;

use app\assets\AppAsset;
use yii\web\AssetBundle;
use yii\widgets\ActiveFormAsset;

/**
 * Class InnValidatorAssets
 * Подключает валидацию полей с ИНН в ActiveForm.
 *
 * @package app\components\validators\inn_validator\InnValidator
 */
class InnValidatorAssets extends AssetBundle {
	public $sourcePath = '@app/components/validators/inn_validator/assets';

	public $js = [
		'js/inn_validator.js'
	];

	public $depends = [
		AppAsset::class,
		ActiveFormAsset::class,
	];
}
