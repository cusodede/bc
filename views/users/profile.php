<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\controllers\SiteController;
use app\models\sys\users\Users;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Профиль';

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
	<div class="col-lg-3 col-xl-3 order-lg-1 order-xl-1">
		<div class="card mb-g rounded-top">
			<div class="row no-gutters row-grid">
				<div class="col-12">
					<div class="d-flex flex-column align-items-center justify-content-center p-4">
						<?= Html::img(Users::Current()->currentAvatarUrl, [
							'class' => "rounded-circle shadow-2 img-thumbnail",
							'style' => "width: 160px; height: 160px"
						]) ?>
						<h5 class="mb-0 fw-700 text-center mt-3">
							<?= Users::Current()->username ?>
						</h5>
					</div>
				</div>
				<div class="col-12">
					<div class="p-3 text-center">
						<?= Html::a("<i class='fal fa-fw fa-image-polaroid'></i> Загрузить фото", "#", [
							"data-toggle" => "modal",
							"data-target" => "#cropperModal"
						]) ?>
						<span class="text-primary d-inline-block mx-3">●</span>
						<?= Html::a("<i class='fal fa-fw fa-lock'></i> Обновить пароль", SiteController::to('update-password'), [
							"class" => "btn-link font-weight-bold"
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->render('modal/logo-cropper', [
	'user' => $model
]) ?>