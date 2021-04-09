<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string[] $actions
 */

use app\controllers\TestController;
use app\models\sys\users\CurrentUserHelper;
use yii\helpers\Html;
use yii\web\View;

?>
	Access granted for <?= CurrentUserHelper::model()->username ?>!