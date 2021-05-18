<?php
declare(strict_types = 1);

use yii\bootstrap4\Modal;

?>

<?php Modal::begin(['id' => 'cropperModal', 'title' => 'Фото профиля']) ?>

<?= $this->render('../subviews/logo-cropper', ['modalId' => '#cropperModal']) ?>

<?php Modal::end();
