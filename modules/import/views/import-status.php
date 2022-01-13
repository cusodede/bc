<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ImportStatus $model
 * @var ControllerTrait $controller
 */

use app\modules\import\ImportModuleAssets;
use app\modules\import\models\active_record\ImportStatus;
use pozitronik\helpers\Utils;
use pozitronik\traits\traits\ControllerTrait;
use yii\bootstrap4\Progress;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Url;

ImportModuleAssets::register($this);
$processUrl = Url::to([Yii::$app->controller->id.'/import-status', 'domain' => $model->domain, 'modelClass' => $model->model]);
$this->registerJs(new JsExpression("RefreshProgress('$processUrl','importStatus');"));
?>

<div class="panel">
	<div class="panel-container">
		<div class="panel-content bg-info">
			<div class="panel-tag">
				Загруженные данные обрабатываются на сервере.<br/>
				Статус: <span class="status-label"><?= $model->statusLabel ?></span><br/>
				Обработано: <span
					class="processed"><?= $model->processed?Utils::pluralForm($model->processed, ['строка', 'строки', 'строк']):'N/A' ?></span><br/>
				Импортировано: <span
					class="imported"><?= $model->imported?Utils::pluralForm($model->imported, ['строка', 'строки', 'строк']):'N/A' ?></span><br/>
				С ошибкой (пропущено): <span
					class="skipped"><?= $model->skipped?Utils::pluralForm($model->skipped, ['строка', 'строки', 'строк']):'N/A' ?></span><br/>
				Последняя ошибка: <span class="error"><?= $model->error??'Нет' ?></span>
			</div>
		</div>
		<div class="panel-content">
			<?= Progress::widget([
				'id' => 'importStatus',
				'percent' => $model->percent,
				'label' => "{$model->percent}%"
			]) ?>
		</div>
	</div>
</div>
