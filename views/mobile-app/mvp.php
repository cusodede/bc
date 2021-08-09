<?php
declare(strict_types = 1);

use app\assets\AppAsset;
use app\components\helpers\Html;
use app\controllers\PartnersController;
use app\models\products\Products;

AppAsset::register($this);

$products = Products::find()->all();

$js = <<<JS
$('.connect-checkbox').on('click', async function () {
    const label = document.getElementById(this.id + '-label');
    const image = document.getElementById(this.id + '-gif');
    
    label.style.display = 'none';
    image.style.display = 'block';
    
    const divLog = document.getElementById('event-log');
    divLog.innerHTML = 'Обработка ...';
    
    const data = {id: this.dataset.product};
    if (this.checked) {
        await $.ajax({
			type: "GET",
			url: '/mobile-app/connect',
			data: data
		});
    } else {
        await $.ajax({
			type: "GET",
			url: '/mobile-app/disconnect',
			data: data
		});
    }
    
    let evtSource = new EventSource("/api/products/sse");
	evtSource.onmessage = function(event) {
		const newElement = document.createElement('div');
		newElement.innerHTML = event.data;
		if (event.data !== 'stop') {
			divLog.appendChild(newElement);
		} else {
		    evtSource.close();
		    label.style.display = 'block';
		    image.style.display = 'none';
		}
	}
});

JS;

$this->registerJs($js);
?>

<?php foreach ($products as $key => $product) : ?>
<div class="row">
	<div class="col-12">
		<div class="card mb-g rounded-top rounded-bottom" style="font-size: 14px">
			<div class="row no-gutters">
				<div class="col-12 d-flex flex-row align-items-center">
					<div class="p-2">
						<?= Html::img(PartnersController::to('get-logo', ['id' => $product->relatedPartner->id]), [
							'class' => "rounded-circle shadow-2 img-thumbnail user-logo",
							'style' => "width: 60px;",
						]) ?>
					</div>
					<div class="font-weight-bold">
						<?= $product->name ?>
					</div>
					<div class="p-4 text-right flex-fill">
						<?= $product->relatedPartner->relatedCategory->name ?>
					</div>
				</div>
				<div class="col-12 p-4">
					<?= $product->description ?>
				</div>
				<div class="col-12">
					<div class="p-3 d-flex flex-row justify-content-end">
						<div class="mr-2 font-weight-bold">
							<?= $product->getPaymentShortView() ?>
						</div>
						<div id="customSwitch<?= $key ?>-label" class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input connect-checkbox" id="customSwitch<?= $key ?>"
								   data-product="<?= $product->id ?>">
							<label class="custom-control-label" for="customSwitch<?= $key ?>"></label>
						</div>
						<div id="customSwitch<?= $key ?>-gif" class="loading-gif" style="display: none">
							<img src="/img/theme/loading.gif" alt="" style="width: 20px">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>
