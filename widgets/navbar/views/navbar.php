<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\models\sys\users\Users;
use yii\web\View;
?>

<header id="navbar">
	<div id="navbar-container" class="boxed">
		<div class="navbar-header">
			<ul class="nav navbar-top-links pull-left">
				<?= $this->render('dropdown', [
					'user' => $user
				]) ?>
			</ul>
		</div>
	</div>
</header>

