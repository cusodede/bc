<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 */

use yii\base\Model;
use yii\web\View;

?>

<?= $this->render('@app/views/default/modal/edit', ['model' => $model, 'title' => 'Редактирование информации о продукте']) ?>