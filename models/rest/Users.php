<?php
declare(strict_types = 1);

namespace app\models\rest;

use simialbi\yii2\rest\ActiveRecord;

/**
 * Class Users
 */
class Users extends ActiveRecord {

	/**
	 * {@inheritdoc}
	 */
	public static function modelName() {
		return 'users';
	}

	/**
	 * {@inheritdoc}
	 */
	public static function primaryKey() {
		return ['id'];
	}
}