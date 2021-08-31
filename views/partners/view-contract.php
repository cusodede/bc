<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 */

use app\models\contracts\active_record\RelContractsToProducts;
use app\models\contracts\Contracts;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\View;

?>
<?php foreach ($model->relatedProducts as $products): ?>
	<?php
	$contractsToProducts = RelContractsToProducts::findModel($products->id);
	$contract            = Contracts::findOne(ArrayHelper::getValue($contractsToProducts, 'contract_id'));
	$refsharingRate      = $products->relatedRevShare;
	?>
	<div class="card">
		<div class="card-body">
			<blockquote class="blockquote mb-0">
				<div class="row">
					<div class="col">№<?= $contract->contract_number ?></div>
					<div class="col">№<?= $contract->contract_number_nfs ?></div>
					<div class="w-100"></div>
					<div class="col"><small>Номер договора</small></div>
					<div class="col"><small>Номер контракта</small></div>
					<div class="w-100"></div>
					<br>
					<div class="col">Дата подписания: <?= Yii::$app->formatter->asDate($contract->signing_date) ?></div>
					<div class="w-100"></div>
					<br>
					<div class="col"><small>Продукт</small></div>
					<div class="col"><small>Цена</small></div>
					<div class="col"><small>Условия ставки</small></div>
					<div class="col"><small>% рефшеринга</small></div>
					<div class="w-100"></div>
					<div class="col"><?= $products->name ?></div>
					<div class="col"><?= Yii::$app->formatter->asDecimal($products->price) ?> &#8381;</div>
					<div class="col"><?= $refsharingRate->calc_formula ?></div>
					<div class="col"><?= $refsharingRate->value ?>%</div>
				</div>
			</blockquote>
		</div>
	</div>
	<hr class="hr-dotted">
<?php endforeach; ?>

