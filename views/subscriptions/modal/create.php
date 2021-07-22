<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Subscriptions $model
 */

use yii\web\View;
use app\models\subscriptions\Subscriptions;

?>

<?= $this->render('@app/views/default/modal/create', ['model' => $model, 'title' => 'Создание подписки']) ?>