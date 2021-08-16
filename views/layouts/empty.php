<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\web\View;

AppAsset::register($this);

?>
<!DOCTYPE html>
<?php $this->beginPage(); ?>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<?= Html::csrfMetaTags() ?>
	<title><?= $this->title ?></title>
	<?php $this->head(); ?>
</head>

<body class="mod-bg-1 mod-nav-link mod-skin-light">
<?php $this->beginBody(); ?>

<main id="js-page-content" class="page-content" role="main" style="height: 100%; width: 770px; margin: auto; background-image: url('/img/back.png'); background-repeat: no-repeat; background-position: top center;">
	<div style="width: 390px; margin-left: 95px; margin-top: 160px">
		<?= $content ?>
	</div>
</main>

<div id="event-log" style="position: absolute; width: 30%; min-height: 200px; top: 0; right: 0" class="p-4 card font-weight-bold">
	Лог операций ...
</div>

<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>