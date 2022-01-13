<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\components\widgets\ActiveFieldMap;
use yii\bootstrap4\ActiveForm;
use yii\base\Model;
use yii\web\View;

?>

<?php foreach ($model->attributes() as $attribute): ?>
	<?php if ($model->isAttributeRequired($attribute)): ?>
		<div class="row">
			<div class="col-md-12">
				<?= $form->field($model, $attribute)->widget(ActiveFieldMap::class)->label(false) ?>
			</div>
		</div>
	<?php endif ?>
<?php endforeach; ?>
