<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var SysExport $model
 */

use app\components\helpers\ArrayHelper;
use app\controllers\UsersController;
use app\modules\export\models\SysExport;
use pozitronik\filestorage\widgets\file_list\FileListWidget;
use pozitronik\widgets\BadgeWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model,
	'attributes' => [
		'id',
		'updated_at',
		'created_at',
		'extra_data',
		[
			'attribute' => 'status',
			'value' => static fn(SysExport $model) => ArrayHelper::getValue(SysExport::STATUSES, $model->status),
		],
		[
			'attribute' => 'user',
			'format' => 'raw',
			'value' => static fn(SysExport $model) => BadgeWidget::widget([
				'items' => $model->relatedUser,
				'subItem' => 'username',
				'tooltip' => "id {$model->user}",
				'urlScheme' => UsersController::to('view', ['id' => $model->user])
			]),
		],
		[
			'attribute' => 'storage',
			'format' => 'raw',
			'value' => static fn(SysExport $model) => BadgeWidget::widget([
				'items' => $model->relatedStorage
					?Html::a($model->relatedStorage->filename, ['/s3/download', 'id' => $model->relatedStorage->id])
					:null
			]),
			'label' => 'Файл'
		],
		[
			'attribute' => 'file',
			'format' => 'raw',
			'value' => static fn(SysExport $model):string => FileListWidget::widget([
				'model' => $model,
				'tags' => ['exportRewardsPilot'],
				'allowVersions' => false
			]),
			'label' => 'Файл'
		]
	]
]) ?>
