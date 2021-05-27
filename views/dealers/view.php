<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Dealers $model
 */

use app\models\dealers\Dealers;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model
]) ?>
