<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Sellers $model
 */

use app\models\seller\Sellers;
use pozitronik\widgets\BadgeWidget;
use yii\web\View;
use yii\widgets\DetailView;
use app\models\seller\active_record\SellersAR;

?>

<?= DetailView::widget([
	'model' => $model,
	'attributes' => [
		'id',
		'name',
		'create_date',
		[
			'attribute' => 'stores',
			'format' => 'raw',
			'value' => static function(SellersAR $model):string {
				return BadgeWidget::widget([
					'items' => $model->stores,
					'subItem' => 'name'
				]);
			}
		],
		'deleted:boolean'
	]
]) ?>
