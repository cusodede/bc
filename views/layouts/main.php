<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\ModalHelperAsset;
use app\assets\SmartAdminThemeAssets;
use pozitronik\helpers\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

SmartAdminThemeAssets::register($this);
ModalHelperAsset::register($this);
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
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="commit" content="<?= Utils::LastCommit() ?>">
	<?= Html::csrfMetaTags() ?>
	<title><?= $this->title ?> [<?= Utils::LastCommit() ?>]</title>
	<?php $this->head(); ?>
</head>

<body class="mod-bg-1 header-function-fixed nav-function-fixed mod-nav-link mod-skin-light">
<?php $this->beginBody(); ?>
<div class="page-wrapper">
	<div class="page-inner">
		<?= $this->render('subviews/sidebar') ?>
		<div class="page-content-wrapper">
			<header class="page-header" role="banner">
				<div class="ml-auto d-flex">
					<div>
						<a href="<?= Url::to(['/site/logout']) ?>" class="header-icon" data-toggle="tooltip"
						   data-placement="left" title="" data-original-title="Выйти из системы">
							<i class="fal fa-sign-out"></i>
						</a>
					</div>
				</div>
			</header>
			<main id="js-page-content" class="page-content" role="main">
				<?= $this->render('subviews/breadcrumbs') ?>
				<div class="subheader">
					<h1 class="subheader-title">
						<?= $this->title ?>
					</h1>
				</div>
				<?= $content ?>
			</main>
			<div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div>
			<footer class="page-footer" role="contentinfo">
				<div class="d-flex align-items-center flex-1 text-muted">
						<span class="hidden-md-down fw-700">
							<?= date('Y').' © '.Yii::$app->name ?>
						</span>
				</div>
			</footer>
			<?= $this->render('subviews/js-color-profile') ?>
		</div>
	</div>
</div>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>