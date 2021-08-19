<?php
declare(strict_types = 1);

namespace app\modules\graphql;

use app\models\sys\users\Users;
use cusodede\jwt\JwtHttpBearerAuth;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use pozitronik\traits\traits\ModuleTrait;
use Yii;
use yii\base\Module;

/**
 * Class GraphqlModule
 * @package app\modules\graphql
 */
class GraphqlModule extends Module
{
	use ModuleTrait;

	public function init(): void
	{
		parent::init();
		Yii::$container->set(JwtHttpBearerAuth::class, [
			'jwtOptionsCallback' => static fn(Users $user): array => [
				'validationConstraints' => [
					new SignedWith(Yii::$app->jwt->signer, Yii::$app->jwt->signerKey),
					LooseValidAt::class
				]
			]
		]);
	}
}
