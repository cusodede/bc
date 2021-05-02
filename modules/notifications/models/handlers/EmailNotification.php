<?php
declare(strict_types = 1);

namespace app\modules\notifications\models\handlers;

use Amp\Promise;
use pozitronik\sys_exceptions\models\SysExceptions;
use Throwable;
use Yii;
use yii\base\Model;

/**
 * Class EmailNotification
 * @property string|array|null $view the view to be used for rendering the message body, @see MailerInterface::compose
 * @property array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
 * @property string|string[] $from sender email address
 * @property string|string[] $to receiver email address
 * @property string $subject message subject
 * @property string|null $htmlBody
 * @property string|null $textBody
 *
 * @property string|null $attachContent attachment file content.
 * @property string[] $attachFiles list of attachment filenames
 */
class EmailNotification extends Model implements NotificationInterface {
	public $view;
	public $params = [];
	public $from;
	public $to;
	public $subject;
	public $htmlBody;
	public $textBody;
	public $attachContent;
	public $attachFiles = [];

	public function process():bool {
		try {
			$email = Yii::$app->mailer->compose($this->view, $this->params)
				->setFrom($this->from)
				->setTo($this->to)
				->setSubject($this->subject);

			if (null !== $this->attachContent) $email->attachContent($this->attachContent);
			foreach ($this->attachFiles as $attachFile) {
				$email->attach($attachFile);
			}
			if (null !== $this->htmlBody) $email->setHtmlBody($this->htmlBody);
			if (null !== $this->textBody) $email->setTextBody($this->textBody);

			return $email->send();
		} catch (Throwable $t) {
			SysExceptions::log($t);
			return false;
		}
	}

	public function call(callable $callback, ...$args):Promise {
		return $this->process();
	}

	/**
	 * @inheritDoc
	 */
	public function onResolve(callable $onResolved) {
		return $this->process();
	}
}