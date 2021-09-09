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
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use pozitronik\helpers\ArrayHelper;
use yii\web\View;
use app\models\partners\Partners;

?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'surname') ?>
<?= $form->field($model, 'login') ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'partner_id')->widget(Select2::class, [
	'data' => ArrayHelper::map(Partners::find()->active()->all(), 'id', 'name'),
	'pluginOptions' => [
		'multiple' => false,
		'allowClear' => true,
		'placeholder' => 'Выберите партнера',
		'tags' => true
	]
]) ?>
<?= $form->field($model, 'phones')->widget(Select2::class, [
	'showToggleAll' => false,
	'options' => [
		'placeholder' => 'Введите один или несколько телефонных номеров',
		'multiple' => true
	],
	'pluginOptions' => [
		'tags' => true,
		'tokenSeparators' => [',', ' '],
		'maximumInputLength' => 12
	]
]) ?>
<?= $form->field($model, 'comment')->textarea() ?>
<div class="row">
	<div class="col-sm-12">
		<?= ([] === $permissionsList = ArrayHelper::map(Permissions::find()->all(), 'id', 'name'))
			? 'Доступы не созданы'
			: $form->field($model, 'relatedPermissions')
				->widget(MultiSelectListBox::class, ['options' => ['multiple' => true], 'data' => $permissionsList]) ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<?= ([] === $permissionsCollectionsList = ArrayHelper::map(PermissionsCollections::find()->all(), 'id', 'name'))
			? 'Группы доступов не созданы'
			: $form->field($model, 'relatedPermissionsCollections')
				->widget(MultiSelectListBox::class, ['options' => ['multiple' => true], 'data' => $permissionsCollectionsList]) ?>
	</div>
</div>
