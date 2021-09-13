<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Partners $model
 */

use app\models\partners\Partners;
use app\models\revshare_rates\RevShareRates;
use yii\web\View;

?>
<?php foreach ($model->relatedProducts as $products): ?>
	<?php $revShareRates = RevShareRates::find()->where(['product_id' => $products->id])->all(); ?>
	<?php if ($products->relatedContracts): ?>
		<?php foreach ($products->relatedContracts as $contract): ?>
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
							<div class="col">Дата
								подписания: <?= Yii::$app->formatter->asDate($contract->signing_date) ?></div>
							<div class="w-100"></div>
							<br>
							<div class="col"><small>Продукт</small></div>
							<div class="col"><small>Цена</small></div>
							<div class="col"><small>Пороговое значение</small></div>
							<div class="col"><small>Процентная ставка</small></div>
							<div class="w-100"></div>

							<div class="col"><?= $products->name ?></div>
							<div class="col"><?= Yii::$app->formatter->asDecimal($products->price) ?> &#8381;</div>
							<?php if ($revShareRates): ?>
								<?php foreach ($revShareRates as $rev): ?>
									<div class="col"><?= $rev->condition_value ?> </div>
									<div class="col"><?= $rev->rate ?> </div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="col"></div>
								<div class="col"></div>
							<?php endif; ?>
							<hr>
						</div>
					</blockquote>
				</div>
			</div>
			<hr class="hr-dotted">
		<?php endforeach; ?>
	<?php endif; ?>
<?php endforeach; ?>

