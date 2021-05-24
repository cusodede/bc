<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Rewards $model
 */

use app\models\reward\active_record\RewardsAR;
use app\models\reward\Rewards;
use pozitronik\widgets\BadgeWidget;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model,
	'attributes' => [
		'id',
		'create_date',
		'value',
		[
			'attribute' => 'operation',
			'format' => 'raw',
			'value' => static function(RewardsAR $model):string {
				return BadgeWidget::widget([
					'items' => $model->refRewardOperation,
					'subItem' => 'name'
				]);
			}
		],
		[
			'attribute' => 'rule',
			'format' => 'raw',
			'value' => static function(RewardsAR $model):string {
				return BadgeWidget::widget([
					'items' => $model->refRewardRule,
					'subItem' => 'name'
				]);
			}
		],
		'comment',
		'deleted:boolean'
	]
]) ?>
