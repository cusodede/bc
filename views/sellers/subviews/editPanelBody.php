<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Sellers $model
 * @var ActiveForm $form
 */

use app\controllers\SellersController;
use app\models\prototypes\seller\Sellers;
use app\models\prototypes\seller\Stores;
use app\widgets\selectmodelwidget\SelectModelWidget;
use kartik\form\ActiveForm;
use yii\web\View;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'stores')->widget(SelectModelWidget::class, [
			'selectModelClass' => Stores::class,
			'selectionQuery' => Stores::find()->active(),
//			'pluginOptions' => [
//				'matcher' => new JsExpression('function(params, data) {return SellersMatchCustom(params, data)}')
//			],
			'options' => ['placeholder' => ''],
			'ajaxSearchUrl' => SellersController::to('ajax-search')
		]) ?>
	</div>
</div>
