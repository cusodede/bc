<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

$userTemplate = '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="suggestion-links"><a href="'.Url::to(['users/profile']).'?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Профиль<a/></div><div class="clearfix"></div>';

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
				'url' => Url::to(['ajax/search-users']).'?term=QUERY&limit=5',
				'wildcard' => 'QUERY'
			]
		],
	]
]) ?>
