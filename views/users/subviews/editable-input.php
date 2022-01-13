<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 * @var string $attribute
 * @var string $url
 */

use app\models\sys\users\Users;
use kartik\editable\Editable;
use app\components\helpers\Html;
use yii\web\View;

?>

<?= Html::label($model->getAttributeLabel($attribute), null, ['class' => 'mb-0 font-weight-bold']) ?>

<?= Editable::widget([
	'model' => $model,
	'attribute' => $attribute,
	'asPopover' => false,
	'formOptions' => ['action' => $url],
	'inlineSettings' => [
		'options' => [
			'class' => 'card panel panel-default mb-0',
		],
		'closeButton' => Html::button('<i class="fas fa-times"></i>', [
			'class' => 'kv-editable-close btn btn-sm btn-outline-secondary',
			'title' => 'Закрыть',
			'style' => 'margin: 2px 4px 0 0;'
		])
	],
	'buttonsTemplate' => '{submit}',
	'options' => ['class' => 'form-control', 'placeholder' => 'Задайте значение...']
]) ?>
