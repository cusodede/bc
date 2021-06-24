<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use pozitronik\core\traits\ActiveQueryExtended;
use yii\db\ActiveQuery as YiiActiveQuery;

/**
 * Trait ActiveQueryTrait
 * Пишем сюда всякие кастомные приколюхи для расширения запросов
 */
class ActiveQuery extends YiiActiveQuery {
	use ActiveQueryExtended;
}