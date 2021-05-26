<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\web\View;
use yii\bootstrap4\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<?= $this->render('subviews/update-passwordPanelBody', compact('model', 'form')) ?>
	</div>
	<div class="panel-footer">
		<?= $this->render('subviews/editPanelFooter', compact('model', 'form')) ?>
		<div class="clearfix"></div>
	</div>
</div>
<?php ActiveForm::end(); ?>
