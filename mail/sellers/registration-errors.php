<?php
declare(strict_types = 1);

use app\models\seller\Sellers;
use yii\bootstrap4\Html;
use yii\web\View;

/**
 * Шаблон письма со ссылкой восстановления пароля
 * @var View $this view component instance
 * @var string $sellerUrl Ссылка на страницу продавца
 * @var Sellers $seller
 * @var array $errors
 */
?>
Здравствуйте,<br>
Продавец <?= $seller->fio ?> не прошел успешно регистрацию в <?= Yii::$app->name ?>.<br>
Необходимо исправить следующие ошибки:<br>
<ul>
	<?php foreach ($errors as $index => $error): ?>
		<li><?= $error ?></li>
	<?php endforeach; ?>
</ul>

Для этого пройдите по указанной <?= Html::a('ссылке', $sellerUrl) ?> или скопируйте в адресную строку браузера адрес:
<br>
<pre><?= $sellerUrl ?></pre>
<br>
С уважением,<br/>
робот поддержки <?= Yii::$app->name ?>.<br>