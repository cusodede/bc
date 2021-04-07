<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Permissions $model
 */

use app\models\sys\permissions\Permissions;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<?= $this->render('subviews/editPanelBody', compact('model', 'form')) ?>

	</div>
	<div class="panel-footer">
		<?= $this->render('subviews/editPanelFooter', compact('model', 'form')) ?>
		<div class="clearfix"></div>
	</div>
</div>
<?php ActiveForm::end(); ?>
