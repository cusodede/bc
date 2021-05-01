<?php
declare(strict_types = 1);

namespace app\modules\notifications;

use pozitronik\core\traits\ModuleExtended;
use yii\base\Module;

/**
 * Class HistoryModule
 *
 * Идея: уведомление представляет собой исходящий канал сообщений. Этот канал может быть любым: email, sms, telegram,
 * сообщение на сайте, etc., и реализуется отдельным классом с общим интерфейсом.
 * Уведомления обрабатываются в файберах (реализация через amphp), и не тормозят исполнение процесса.
 * Если уведомление не получилось отправить (и обработчик имеет возможность проверить это), то оно помечается,
 * как недоставленное. Очередь отправки не реализована.
 */
class NotificationsModule extends Module {
	use ModuleExtended;
}
