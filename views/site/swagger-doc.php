<?php
declare(strict_types = 1);

use app\assets\SwaggerAsset;
use yii\web\View;

/**
 * @var View $this
 * @var string $schemaUrl
 */

SwaggerAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<title>Swagger UI</title>
	<link rel="icon" type="image/png" href="/img/swagger/favicon-32x32.png" sizes="32x32"/>
	<link rel="icon" type="image/png" href="/img/swagger/favicon-16x16.png" sizes="16x16"/>
	<style>
		html {
			box-sizing: border-box;
			overflow: -moz-scrollbars-vertical;
			overflow-y: scroll;
		}

		*,
		*:before,
		*:after {
			box-sizing: inherit;
		}

		body {
			margin: 0;
			background: #fafafa;
		}
	</style>
	<?php $this->head(); ?>
</head>

<body>
<?php $this->beginBody(); ?>
<div id="swagger-ui" data-schema-url="<?= $schemaUrl ?>"></div>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>