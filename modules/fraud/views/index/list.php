<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var FraudCheckStepSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\fraud\components\FraudValidator as FraudValidator;
use app\modules\fraud\models\FraudCheckStep;
use app\modules\fraud\models\FraudCheckStepSearch;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\grid\ActionColumn;

?>

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
					return Html::a('<i class="fas fa-edit"></i>', $url);
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
				if ( ! class_exists($model->fraud_validator)) {
					return 'Валидатор не найден.' . $model->fraud_validator;
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
			'value' => function (FraudCheckStep $model) {
				return $model->getStatusName() ?: "Статус не определен $model->status";
			}
		],

	]
]) ?>

