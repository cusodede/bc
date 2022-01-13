<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $modelClass
 * @var string $domain
 * @var string $action
 * @var ControllerTrait $controller
 */

use app\components\helpers\Html;
use app\modules\import\ImportModule;
use pozitronik\traits\traits\ControllerTrait;
use yii\helpers\Url;
use yii\web\View;

?>

<div class="panel">
	<div class="panel-container">
		<div class="panel-content bg-success">
			Файл успешно поставлен в очередь импорта. Следить за прогрессом загрузки
			<?= Html::link(
				'можно по ссылке',
				Url::to([Yii::$app->controller->id.'/import-status', 'domain' => $domain, 'modelClass' => $modelClass]),
				[],
				Html::NO
			) ?>
		</div>
		<div class="panel-content">
			<?= Html::link(
				'Загрузить ещё что-нибудь',
				ImportModule::to(Yii::$app->controller->id.'/'.$action),
				['class' => 'btn btn-success pull-right'],
				Html::NO
			) ?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>