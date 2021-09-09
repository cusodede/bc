<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Abonents $model
 * @var ActiveDataProvider $dataProvider
 */

use app\models\abonents\Abonents;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\data\ActiveDataProvider;
use yii\web\View;

$modelName = $model->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-view-products-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => 'Все активные продукты абонента',
		'subItem' => 'name'
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?= $this->render('../view-model', compact('dataProvider', 'model')) ?>
<?php Modal::end(); ?>
