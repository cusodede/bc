<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\LoginAsset;
use yii\helpers\Html;
use yii\web\View;

LoginAsset::register($this);
?>
<!DOCTYPE html>
<?php $this->beginPage(); ?>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?= Html::csrfMetaTags() ?>
	<title> <?= Html::encode($this->title) ?></title>
	<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div id="container" class="cls-container">
	<div id="bg-overlay" class="bg-img"></div>
	<div class="cls-content">
		<?= $content ?>
	</div>
</div>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>