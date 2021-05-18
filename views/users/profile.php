<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\web\View;

$this->title = 'Профиль';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('modal/logo-cropper') ?>

<div class="row">
	<div class="col-lg-3 col-xl-3 order-lg-1 order-xl-1">
		<div class="card mb-g rounded-top">
			<div class="row no-gutters row-grid">
				<div class="col-12">
					<div class="d-flex flex-column align-items-center justify-content-center p-4">
						<img id="user-logo" src="/img/avatars/1/avatar.png?1" class="rounded-circle shadow-2 img-thumbnail"
							 style="width: 130px; height: 130px">
						<button type="button" class="btn btn-default waves-effect waves-themed mt-3" data-toggle="modal"
								data-target="#cropperModal">
							Обновить фото профиля
						</button>
						<h5 class="mb-0 fw-700 text-center mt-3">
							Dr. Codex Lantern
							<small class="text-muted mb-0">Toronto, Canada</small>
						</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>