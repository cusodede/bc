<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PermissionsCollections $model
 */

use app\models\sys\permissions\PermissionsCollections;
use yii\web\View;

?>

<?= $this->render('@app/views/default/modal/create', ['model' => $model, 'title' => 'Создание группы пермиссий']) ?>