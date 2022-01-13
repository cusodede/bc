<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var FilterForm $form
 * @var ControllerTrait $controller
 */

use app\modules\export\ExportModule;
use app\modules\export\forms\pilot\FilterForm;
use kartik\daterange\DateRangePicker;
use pozitronik\traits\traits\ControllerTrait;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

?>

<?php $activeForm = ActiveForm::begin(); ?>
	<div class="panel">
		<div class="panel-hdr"></div>
		<div class="panel-container show">
			<div class="panel-content">
				<?php if (Yii::$app->session->hasFlash('success')): ?>
					<div class="alert alert-info" role="alert">
						Запрос на выгрузку поставлен в очередь. Результат будет доступен на
						странице <?= Html::a('Результаты ', ExportModule::to('export')) ?>
					</div>
				<?php endif; ?>
				<div class="row">
					<div class="col-md-6">
						<?= $activeForm->field($form, 'date_range', [
							'options' => ['class' => 'drp-container mb-2']
						])->widget(DateRangePicker::class, [
						]) ?>
					</div>
				</div>
			</div>
			<div class="panel-content">
				<?= Html::submitButton('Экспорт', ['class' => 'btn btn-success float-right']) ?>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>