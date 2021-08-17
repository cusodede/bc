<?php
declare(strict_types = 1);

namespace app\modules\api\models;

use app\models\site\RestorePasswordForm;
use Yii;

class FrontRestorePasswordForm extends RestorePasswordForm
{
	/**
	 * {@inheritDoc}
	 */
	public static function getRestoreUrl(string $restoreCode): string
	{
		return Yii::$app->params['frontUrl'] . '/set-password?' . http_build_query(['code' => $restoreCode]);
	}
}