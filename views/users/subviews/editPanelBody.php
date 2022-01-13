<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 * @var ActiveForm $form
 */

use app\models\sys\permissions\Permissions;
use app\models\sys\permissions\PermissionsCollections;
use app\models\sys\users\Users;
use cusodede\multiselect\MultiSelectListBox;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use pozitronik\helpers\ArrayHelper;
use yii\web\View;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'username')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'login')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'email')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'phones')->widget(Select2::class, [
			'showToggleAll' => false,
			'options' => [
				'placeholder' => '',
				'multiple' => true
			],
			'pluginOptions' => [
				'tags' => true,
				'tokenSeparators' => [',', ' '],
				'maximumInputLength' => 12,
				'minimumInputLength' => 10
			]
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'comment')->textarea() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= ([] === $permissionsList = ArrayHelper::map(Permissions::find()->all(), 'id', 'name'))?'Доступы не созданы':$form->field($model, 'relatedPermissions')->widget(MultiSelectListBox::class, ['options' => ['multiple' => true,],
			'data' => $permissionsList,]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= ([] === $permissionsCollectionsList = ArrayHelper::map(PermissionsCollections::find()->all(), 'id', 'name'))?'Группы доступов не созданы':$form->field($model, 'relatedPermissionsCollections')->widget(MultiSelectListBox::class, ['options' => ['multiple' => true,],
			'data' => $permissionsCollectionsList,]) ?>
	</div>
</div>

