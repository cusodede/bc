<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ImportJob $job
 * @var ControllerTrait $controller
 */

use app\components\helpers\Html;
use app\modules\import\models\ImportJob;
use pozitronik\traits\traits\ControllerTrait;
use yii\web\View;

?>

<div class="panel">
	<div class="panel-container">
		<div class="panel-content bg-success">
			Файл успешно поставлен в очередь импорта. Следить за прогрессом загрузки
			<?= Html::link('можно по ссылке', $controller::to('import-status', ['domain' => $job->domain, 'modelClass' => $job->model]), [], Html::NO) ?>
		</div>
		<div class="panel-content">
			<?= Html::link('Загрузить ещё что-нибудь', $controller::to('import'), ['class' => 'btn btn-success pull-right'], Html::NO) ?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>