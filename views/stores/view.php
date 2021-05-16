<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Stores $model
 */

use app\models\prototypes\seller\Stores;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model
]) ?>
