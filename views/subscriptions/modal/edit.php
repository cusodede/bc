<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Subscriptions $model
 */

use yii\web\View;
use app\models\subscriptions\Subscriptions;

?>

<?= $this->render('@app/views/default/modal/edit', ['model' => $model, 'title' => 'Редактирование информации о подписке']) ?>