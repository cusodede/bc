<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Sellers $model
 */

use app\models\prototypes\seller\Sellers;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model
]) ?>
