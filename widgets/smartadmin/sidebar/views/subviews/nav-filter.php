<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use yii\web\View;

?>
<div class="nav-filter">
	<div class="position-relative">
		<input type="text" id="nav_filter_input" placeholder="Поиск" class="form-control" tabindex="0">
		<a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
		   data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
			<i class="fal fa-chevron-up"></i>
		</a>
	</div>
</div>