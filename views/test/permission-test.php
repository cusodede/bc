<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use app\models\sys\users\CurrentUserHelper;
use yii\web\View;

?>
	Access granted for <?= CurrentUserHelper::model()->username ?>!