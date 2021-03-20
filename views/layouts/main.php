<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\AppAsset;
use app\models\sys\users\CurrentUser;
use pozitronik\helpers\Utils;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Menu;

AppAsset::register($this);
?>
<!DOCTYPE html>
<?php $this->beginPage(); ?>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="commit" content="<?= Utils::LastCommit() ?>">
	<?= Html::csrfMetaTags() ?>
	<title><?= $this->title ?></title>
	<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div id="container" class="mainnav-fixed print-content">
	<?php /* SearchWidget::widget()  todo*/?>
	<?= Menu::widget([
		'items' => [
			['label' => 'Home', 'url' => CurrentUser::homeUrl()],
			['label' => CurrentUser::model()->username, 'url' => ["/users/profile", "id" => CurrentUser::model()->id]]
		]
	]) ?>
	<div class="boxed">
		<div id="content-container">
			<div id="page-content">
				<?= $content ?>
			</div>
		</div>
	</div>
</div>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>