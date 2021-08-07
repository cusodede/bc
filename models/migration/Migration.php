<?php
declare(strict_types = 1);

namespace app\models\migration;

use yii\db\ActiveRecord;

/**
 * Class Migration
 * @package app\models\migration
 *
 * @property string $version
 * @property int $apply_time
 */
class Migration extends ActiveRecord {
	/**
	 * @return string
	 */
	public static function tableName():string {
		return 'migration';
	}
}
