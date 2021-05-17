<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Stores $model
 */

use app\models\store\active_record\StoresAR;
use app\models\store\Stores;
use pozitronik\widgets\BadgeWidget;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model,
	'attributes' => [
		'id',
		'name',
		'create_date',
		[
			'attribute' => 'sellers',
			'format' => 'raw',
			'value' => static function(StoresAR $model):string {
				return BadgeWidget::widget([
					'items' => $model->sellers,
					'subItem' => 'name'
				]);
			}
		],
		'deleted:boolean'
	]
]) ?>
