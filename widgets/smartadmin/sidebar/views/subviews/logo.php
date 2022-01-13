<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use yii\bootstrap4\Html;
use yii\web\View;

?>

<div class="page-logo">
	<?= Html::a(
		Html::img('/img/theme/logo.png', [/*todo: вынести в стили*/
			'aria-roledescription' => 'logo',
			'alt' => Yii::$app->name
		]).
		Html::tag('span', Yii::$app->name, ['class' => 'page-logo-text mr-1']).
		Html::tag('span', '', ['class' => 'position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2']),
		'/',
		[
			'class' => 'page-logo-link press-scale-down d-flex align-items-center position-relative'
		]
	) ?>
</div>
