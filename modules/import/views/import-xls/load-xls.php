<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ImportModel $model
 * @var ControllerTrait $controller
 */

use app\modules\import\models\ImportModel;
use pozitronik\traits\traits\ControllerTrait;
use pozitronik\filestorage\widgets\file_input\FileInputWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

?>

<?php $form = ActiveForm::begin(['id' => 'import-xls']); ?>
	<div class="panel">
		<div class="panel-container">
			<div class="panel-content">
				<div class="row">
					<div class="col-md-12">
						<?= $form->field($model, 'loadXls')->widget(FileInputWidget::class)->label('Загрузка Xls') ?>
					</div>
				</div>
			</div>
			<div class="panel-content">
				<?= Html::submitButton('Импорт', [
					'class' => 'btn btn-success pull-right',
					'data-method' => 'POST',
					'data-confirm' => 'Импорт выбранного файла. Продолжить?',
				]) ?>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>