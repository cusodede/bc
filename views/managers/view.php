<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Managers $model
 */

use app\models\managers\Managers;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model,
	'attributes' => [
		'create_date',
		'update_date',
		[
			'attribute' => 'userId',
			'value' => static function(Managers $model) {
				return $model->relatedUser->id??null;
			}
		],
		[
			'attribute' => 'userLogin',
			'value' => static function(Managers $model) {
				return $model->relatedUser->login??null;
			}
		],
		[
			'attribute' => 'userEmail',
			'value' => static function(Managers $model) {
				return $model->relatedUser->email??null;
			}
		],
		'surname',
		'name',
		'patronymic',
		'deleted:boolean'
	]
]) ?>
