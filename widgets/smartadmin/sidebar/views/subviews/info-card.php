<?php
declare(strict_types = 1);

use app\controllers\UsersController;
use yii\helpers\Html;

?>
<div class="info-card">
	<?= Html::img(UsersController::to('logo-get'), ['class' => 'profile-image rounded-circle user-logo']) ?>
	<div class="info-card-text">
		<a href="#" class="d-flex align-items-center text-white">
			<span class="text-truncate text-truncate-sm d-inline-block">Dr. Cox</span>
		</a>
		<span class="d-inline-block text-truncate text-truncate-sm">Toronto, Canada</span>
	</div>
	<img src="/img/theme/card-backgrounds/cover-6-lg.png" class="cover" alt="cover">
	<a href="#" onclick="return false;" class="pull-trigger-btn"
	   data-action="toggle"
	   data-target=".page-sidebar"
	   data-class="list-filter-active"
	   data-focus="nav_filter_input">
		<i class="fal fa-angle-down"></i>
	</a>
</div>