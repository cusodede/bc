<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\models\sys\users\Users;
use yii\base\View;
use yii\helpers\Html;

?>

<li class="mega-dropdown">
	<a href="#" class="mega-dropdown-toggle">
		<div class="navbar-header">
			<div class="navbar-brand">
				<div class="start"></div>
			</div>
		</div>
	</a>
	<div class="dropdown-menu mega-dropdown-menu">
		<div class="row">
			<div class="col-md-4">
				<ul class="list-unstyled">
					<li class="dropdown-header"><?= $user->username ?>
						<div class="text-sm">
							<?= $user->comment ?>
						</div>
					</li>
					<li>
						<?= Html::a("Профиль", ["/users/profile", "id" => $user->id]) ?>
					</li>
					<li>
						<?= Html::a("Редактировать", ["/users/update", "id" => $user->id]) ?>
					</li>
				</ul>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= Html::a("Выйти", ['/site/logout'], ['class' => 'btn btn-primary pull-right']) ?>
				</div>
			</div>
		</div>
	</div>
</li>