<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 */

use yii\base\Model;
use yii\web\View;

?>

<?= $this->render('@app/views/default/modal/create', ['model' => $model, 'title' => 'Создание шаблона']) ?>