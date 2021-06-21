<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var FraudCheckStepSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var Notifications[] $notifications
 */

use app\modules\fraud\components\FraudValidator;
use app\modules\fraud\models\FraudCheckStep;
use app\modules\fraud\models\FraudCheckStepSearch;
use app\modules\notifications\models\Notifications;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\grid\ActionColumn;

?>
<?php if (!empty($notification)): ?>
	<div class="alert alert-success">
		<?= $notification ?>
	</div>
<?php endif; ?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'summary' => false,
	'showOnEmpty' => true,
	'columns' => [
		[
			'class' => ActionColumn::class,
			'template' => '{repeat-validate}',
			'buttons' => [
				'repeat-validate' => static function(string $url, FraudCheckStep $model) {
					$form = Html::beginForm('/fraud/index/list', 'POST', ['class' => 'd-inline-block form-inline']);
					$form .= Html::hiddenInput('repeat_validate_id', $model->id);
					$form .= Html::submitButton('<i class="fas fa-redo"></i>', ['class' => 'btn']);
					$form .= Html::endForm();
					return $form;
				}
			],
		],
		[
			'attribute' => 'id',
			'header' => 'Заказ'
		],
		'entity_id',
		'entity_class',
		[
			'attribute' => 'fraud_validator',
			'value' => static function(FraudCheckStep $model) {
				if (!class_exists($model->fraud_validator)) {
					return 'Валидатор не найден.'.$model->fraud_validator;
				}

				/**
				 * @var FraudValidator $validator
				 */
				$validator = new $model->fraud_validator();
				return $validator->name();
			}
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'status',
			'format' => 'raw',
			'filter' => FraudCheckStep::$statusesWithNames,
			'value' => static function(FraudCheckStep $model) {
				return $model->getStatusName()?:"Статус не определен $model->status";
			}
		],
		[
			'attribute' => 'step_info',
			'value' => static function(FraudCheckStep $model) {
				return ArrayHelper::getValue($model->step_info, 'fraud_message');
			}
		]
	]
])
?>

