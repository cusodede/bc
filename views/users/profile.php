<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\helpers\Url;
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
						<img id="user-logo" src="/img/avatars/1/avatar.png" class="rounded-circle shadow-2 img-thumbnail"
							 style="width: 160px; height: 160px">
						<h5 class="mb-0 fw-700 text-center mt-3">
							<?= Yii::$app->user->identity->username ?>
						</h5>
					</div>
				</div>
				<div class="col-12">
					<div class="p-3 text-center">
						<a href="javascript:void(0);" class="btn-link font-weight-bold"
						   data-toggle="modal"
						   data-target="#cropperModal">
							<i class="fal fa-fw fa-image-polaroid"></i> Загрузить фото
						</a>
						<span class="text-primary d-inline-block mx-3">●</span>
						<a href="<?= Url::to(['/site/update-password'])?>" class="btn-link font-weight-bold">
							<i class="fal fa-fw fa-lock"></i> Обновить пароль
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>