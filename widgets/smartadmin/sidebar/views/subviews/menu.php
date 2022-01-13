<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $items
 */

use app\widgets\smartadmin\menu\MenuWidget;
use yii\web\View;

?>

<?= MenuWidget::widget([
	'options' => [
		'id' => 'js-nav-menu',
		'class' => 'nav-menu'
	],
	'activateParents' => true,
	'items' => $items,
]) ?>