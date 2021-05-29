<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ImportModel $model
 * @var ControllerTrait $controller
 */

use app\modules\import\models\ImportModel;
use pozitronik\core\traits\ControllerTrait;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel">
		<div class="panel-container">
			<div class="panel-content bg-success">
				Файл успешно импортирован в промежуточную таблицу. Импорт содержит <?= $model->count ?> записей.
			</div>
			<div class="panel-content bg-info">
				Нажмите "Далее" для экспорта в основные таблицы.<br/>
				Если браузер остановит работу, просто обновите страницу, импорт продолжится с места остановки.
			</div>
			<div class="panel-content">
				<?= Html::a('Далее', $controller::to('process-import', ['domain' => $model->domain, 'modelClass' => $model->model]), [
					'class' => 'btn btn-success pull-right',
				]) ?>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>