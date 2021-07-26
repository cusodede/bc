<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 */

use app\models\sys\users\Users;
use yii\web\View;

?>

<?= $this->render('@app/views/default/modal/create', ['model' => $model, 'title' => 'Создание пользователя']) ?>