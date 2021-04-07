<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PermissionsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\controllers\PermissionsController;
use app\models\sys\permissions\PermissionsSearch;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

?>
<?= GridConfig::widget([
	'id' => 'permissions-index-grid',
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['разрешение', 'разрешения', 'разрешений']).")":" (нет разрешений)"),
		],
		'summary' => null !== $searchModel?Html::a('Новое разрешение', PermissionsController::to('create'), ['class' => 'btn btn-success summary-content']):null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Новое разрешение', PermissionsController::to('create'), ['class' => 'btn btn-success']),
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true
	])
]) ?>