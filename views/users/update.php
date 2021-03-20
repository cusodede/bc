<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord?'Добавление пользователя':"Изменение пользователя {$model->username}";
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-control">
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
		</div>

		<div class="panel-body">
			<div class="row">

				<div class="col-md-9">
					<div class="row">
						<div class="col-md-12">
							<?= $form->field($model, 'username')->textInput(['maxlength' => 50]) ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'login')->textInput(['maxlength' => 50]) ?>
						</div>
						<div class="col-md-6">
							<?php if ($model->isNewRecord): ?>
								<?= $form->field($model, 'password')->textInput(['maxlength' => 50])->hint('При входе пользователю будет предложено сменить пароль.') ?>
							<?php else: ?>
								<?= $form->field($model, 'update_password')->textInput(['maxlength' => 50, 'value' => false])->hint('Пароль пользователя будет сброшен на введённый.') ?>
							<?php endif; ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'email')->textInput(['maxlength' => 50]) ?>
						</div>
						<div class="col-md-6">
						</div>

						<div class="col-md-6">
							<?= $form->field($model, 'comment')->label('Комментарий пользователя') ?>
						</div>
						<div class="col-md-6">
						</div>


					</div>
				</div>
			</div>

		</div>

		<div class="panel-footer">
			<div class="btn-group">
				<?= Html::submitButton($model->isNewRecord?'Сохранить':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
				<?php if ($model->isNewRecord): ?>
					<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']) ?>
				<?php endif ?>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>