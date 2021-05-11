<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\ModalHelperAsset;
use app\assets\SmartAdminThemeAssets;
use pozitronik\helpers\Utils;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
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
<?php if (Yii::$app->user->isGuest || ArrayHelper::getValue(Yii::$app->user->identity, 'is_pwd_outdated', false)) { ?>
	<div class="panel panel-trans text-center">
		<div class="panel-heading">
			<h1 class="error-code text-primary">Не пущу!</h1>
		</div>
		<div class="panel-body">
			<p>Пользователь не авторизован</p>
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw text-primary"></i>
			<div class="pad-top"><a class="btn-link text-semibold" href="/">Авторизоваться</a></div>
		</div>
	</div>
<?php } else { ?>
	<div class="page-wrapper">
		<div class="page-inner">
			<?= $this->render('subviews/sidebar') ?>
			<div class="page-content-wrapper">
				<header class="page-header" role="banner">
					<!-- header content -->
				</header>
				<main id="js-page-content" class="page-content" role="main">
					<?= $this->render('subviews/breadcrumbs') ?>
					<div class="subheader">
						<h1 class="subheader-title">
							<?= $this->title ?>
						</h1>
					</div>
					<div class="row">
						<div class="col-md-12 col-xl-12">
							<?= $content ?>
						</div>
					</div>
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
<?php } ?>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>