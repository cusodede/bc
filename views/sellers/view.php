<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Sellers $model
 */

use app\controllers\UploadsController;
use app\models\core\prototypes\ProjectConstants;
use app\models\seller\Sellers;
use pozitronik\widgets\BadgeWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use app\models\seller\active_record\SellersAR;

?>

<?= DetailView::widget([
	'model' => $model,
	'attributes' => [
		'create_date',
		'update_date',
		'surname',
		'name',
		'patronymic',
		[
			'attribute' => 'gender',
			'value' => static function(Sellers $model) {
				return ArrayHelper::getValue(ProjectConstants::GENDER, $model->gender);
			},
		],
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
		'birthday',
		'login',
		'email',
		'is_resident:boolean',
		[
			'attribute' => 'passport',
			'value' => static function(Sellers $model) {
				return "{$model->passport_series} {$model->passport_number}";
			}
		],
		'passport_whom',
		'passport_when',
		'reg_address',
		'entry_date',
		'keyword',
		'is_wireman_shpd:boolean',
		'deleted:boolean',
		[
			'attribute' => 'uploadedFiles',
			'format' => 'raw',
			'value' => static function(Sellers $model):string {
				$uploads = [];
				foreach ($model->uploads as $upload) {
					$uploads[] = Html::a(
						'Скачать',
						UploadsController::to('download', ['id' => $upload->id])
					);
				}
				return implode('<br>', $uploads);
			}
		]
	]
]) ?>
