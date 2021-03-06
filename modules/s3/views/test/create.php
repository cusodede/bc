<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $buckets
 * @var CloudStorage $model
 */

use app\modules\s3\models\cloud_storage\CloudStorage;
use yii\bootstrap4\ActiveForm;
use yii\web\View;

?>

<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<div class="panel">
	<div class="panel-hdr">
	</div>
	<div class="panel-container show">
		<div class="panel-content">
			<?= $this->render('subviews/editPanelBody', compact('model', 'form', 'buckets')) ?>
		</div>
		<div class="panel-content">
			<?= $form->errorSummary($model) ?>
			<?= $this->render('subviews/editPanelFooter', compact('model', 'form', 'buckets')) ?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
