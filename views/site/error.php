<?php
declare(strict_types = 1);
/**
 * Шаблон страницы ошибки
 * @var View $this
 * @var HttpException $exception
 */

use yii\helpers\Html;
use yii\web\View;
use yii\web\HttpException;

$message = $exception->getMessage();
$this->title = $message;
?>

<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="panel panel-trans text-center">
			<div class="panel-heading">
				<h1 class="error-code text-primary"><?= Html::encode($exception->statusCode) ?></h1>
			</div>
			<div class="panel-body">
				<p><?= nl2br(Html::encode($message)) ?></p>
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw text-primary"></i>
				<div class="pad-top"><a class="btn-link text-semibold" href="/">На главную</a></div>
			</div>
		</div>
	</div>
</div>
