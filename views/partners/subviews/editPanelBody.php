<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\models\common\RefPartnersCategories;
use kartik\form\ActiveForm;
use pozitronik\filestorage\widgets\file_input\FileInputWidget;
use yii\base\Model;
use yii\web\View;
use kartik\select2\Select2;

?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'inn') ?>
<?= $form->field($model, 'category_id')->widget(Select2::class, [
	'data'          => RefPartnersCategories::mapData(),
	'pluginOptions' => [
		'multiple'    => false,
		'allowClear'  => true,
		'placeholder' => 'Выберите категорию партнера',
		'tags'        => true
	]
]) ?>
<div class="row mb-3">
	<div class="col-md-6">
		<?= $form->field($model, 'phone')->textInput() ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'email')->textInput() ?>
	</div>
</div>
<?= $form->field($model, 'logo')->widget(FileInputWidget::class, [
	'allowDownload' => false,
	'allowVersions' => false
]) ?>
<?= $form->field($model, 'comment')->textarea() ?>

