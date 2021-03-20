<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\helpers\Html;

$this->title = "Профиль пользователя {$model->username}";
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("graphControl = new GraphControl(_.$('user-profile-tree-container'), '-1', $model->id); graphControl.network.on('afterDrawing', function() {graphControl.fitAnimated()})", View::POS_END);
$this->registerJs("$('#user-profile-tree-container').css({'position':'relative'}) ", View::POS_END);
?>
<div class="panel panel-default profile-panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>
	<div class="clearfix"></div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<label>Должность:</label>
			</div>
			<div class="col-md-3">
				<label>Профиль:</label>
			</div>
			<div class="col-md-3">
				<label>Почта:</label>
				<?= $model->email ?>
			</div>
			<div class="col-md-3"></div>
		</div>
		<div class="row">
			<div class="col-md-8">
			</div>
			<div class="col-md-4">
				<div id="user-profile-tree-container">
				</div>
			</div>

		</div>
		<div class="row">

		</div>
	</div>

</div>