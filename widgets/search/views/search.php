<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use app\controllers\AjaxController;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\UsersController;
use kartik\typeahead\Typeahead;
use yii\web\JsExpression;
use yii\web\View;

$userTemplate = '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="suggestion-links"><a href="'.UsersController::to('profile').'?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Профиль<a/></div><div class="clearfix"></div>';
$permissionTemplate = '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="clearfix"></div><div class="suggestion-secondary">{{controller}}</div><div class="suggestion-links"><a href="'.PermissionsController::to('edit').'?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Редактировать<a/></div><div class="clearfix"></div>';
$permissionCollectionsTemplate = '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="suggestion-links"><a href="'.PermissionsCollectionsController::to('edit').'?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Редактировать<a/></div><div class="clearfix"></div>';

?>
<?= Typeahead::widget([
	'container' => [
		'class' => 'pull-left search-box'
	],
	'name' => 'search',
	'readonly' => false,
	'options' => [
		'placeholder' => 'Поиск',
		'autocomplete' => 'off'
	],
	'pluginOptions' => [
		'highlight' => true,
		'minLength' => 3
	],
//	'pluginEvents' => [
//		"typeahead:select" => "function(e, o) {open_result(o)}",
//		"typeahead:close" => "function(e, o) {open_result(o)}"
//	],
	'dataset' => [
		[
			'limit' => 5,
			'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('html')",
			'display' => 'name',
			'templates' => [
				'suggestion' => new JsExpression("Handlebars.compile('{$userTemplate}')"),
				'header' => '<h3 class="suggestion-header">Пользователи</h3>'
			],
			'remote' => [
				'url' => AjaxController::to('search-users').'?term=QUERY&limit=5',
				'wildcard' => 'QUERY'
			]
		],
		[
			'limit' => 5,
			'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('html')",
			'display' => 'name',
			'templates' => [
				'suggestion' => new JsExpression("Handlebars.compile('{$permissionTemplate}')"),
				'header' => '<h3 class="suggestion-header">Разрешения</h3>'
			],
			'remote' => [
				'url' => AjaxController::to('search-permissions').'?term=QUERY&limit=5',
				'wildcard' => 'QUERY'
			]
		],
		[
			'limit' => 5,
			'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('html')",
			'display' => 'name',
			'templates' => [
				'suggestion' => new JsExpression("Handlebars.compile('{$permissionCollectionsTemplate}')"),
				'header' => '<h3 class="suggestion-header">Группы разрешений</h3>'
			],
			'remote' => [
				'url' => AjaxController::to('search-permissions-collections').'?term=QUERY&limit=5',
				'wildcard' => 'QUERY'
			]
		],
	]
]) ?>
