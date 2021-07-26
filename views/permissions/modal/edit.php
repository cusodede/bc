<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Permissions $model
 */

use app\models\sys\permissions\Permissions;
use yii\web\View;

?>

<?= $this->render('@app/views/default/modal/edit', ['model' => $model, 'title' => 'Редактирование информации о пермиссии']) ?>