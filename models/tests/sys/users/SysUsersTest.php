<?php
declare(strict_types = 1);

namespace app\models\tests\sys\users;

use app\models\sys\permissions\active_record\relations\RelUsersToPermissions;
use app\models\sys\permissions\Permissions;
use app\models\sys\users\Users;
use Faker\Factory;
use Yii;

class SysUsersTest extends Users {

	public static function createAdmin():self {
		$self = new self();
		$self->login = 'admin';
		$self->username = 'admin';
		$self->password = 'admin';
		$self->email = 'admin@admin.ru';
		$self->comment = 'Системный администратор';
		$self->create_date = date('Y-m-d');
		$self->salt = Yii::$app->security->generateRandomString();
		return $self;
	}

	public static function create():self {
		$faker = Factory::create();

		$self = new self();
		$self->login = $self->username = $faker->userName;
		$self->password = Yii::$app->security->generateRandomString();
		$self->email = $faker->email;
		$self->comment = $faker->name;
		$self->create_date = date('Y-m-d');
		$self->salt = Yii::$app->security->generateRandomString();
		return $self;
	}

	public function withPermission(Permissions $permission):self {
		$relation = new RelUsersToPermissions();
		$relation->user_id = $this->id;
		$relation->permission_id = $permission->id;
		$relation->save();
		return $this;
	}
}