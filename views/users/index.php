<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\sys\users\UsersSearch;
use pozitronik\helpers\Utils;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)"),
	],
	'summary' => null !== $searchModel?Html::a('Новый пользователь', Url::to(['users/create']), ['class' => 'btn btn-success summary-content']):null,
	'showOnEmpty' => true,
	'emptyText' => Html::a('Новый пользователь', Url::to(['users/create']), ['class' => 'btn btn-success']),
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true
]) ?>