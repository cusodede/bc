<?php
declare(strict_types = 1);

namespace app\modules\api\models;

use app\models\site\RestorePasswordForm;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class FrontRestorePasswordForm
 */
class FrontRestorePasswordForm extends RestorePasswordForm
{
	/**
	 * {@inheritDoc}
	 * @throws Throwable
	 */
	public static function getRestoreUrl(string $restoreCode): string
	{
		return ArrayHelper::getValue(Yii::$app->params, 'frontUrl', new InvalidConfigException('params.frontUrl parameter not set')) . '/set-password?' . http_build_query(['code' => $restoreCode]);
	}
}