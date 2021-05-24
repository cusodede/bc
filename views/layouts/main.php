<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\AppAsset;
use app\assets\ModalHelperAsset;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\SiteController;
use app\controllers\UsersController;
use app\models\sys\users\Users;
use app\modules\history\HistoryModule;
use app\widgets\search\SearchWidget;
use pozitronik\filestorage\FSModule;
use pozitronik\helpers\Utils;
use pozitronik\references\ReferencesModule;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use app\controllers\DbController;

AppAsset::register($this);
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
				<div class="subheader fa-pull-left" style="margin-bottom: unset">
					<h1 class="subheader-title">
						<?= $this->title ?>
					</h1>
				</div>
				<div class="subheader fa-pull-left" style="margin-bottom: unset">
					<?= $this->render('subviews/breadcrumbs') ?>
				</div>

				<div class="ml-auto d-flex">
					<div class="subheader fa-pull-right" style="margin-bottom: unset">
						<?= SearchWidget::widget() ?>
					</div>
					<div>
						<?= Html::a('<i class="fal fa-sign-out"></i>', SiteController::to('logout'), [
							'class' => "header-icon",
							'data-toggle' => "tooltip",
							'data-placement' => "bottom",
							'title' => "",
							'data-original-title' => "Выйти из системы"
						]) ?>
					</div>
				</div>
			</header>
			<main id="js-page-content" class="page-content" role="main">
				<div class="subheader">
					<h1 class="subheader-title">
						<?= $this->title ?>
					</h1>
				</div>
				<div>
					<?= $this->render('subviews/breadcrumbs') ?>
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