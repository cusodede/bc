<?php
declare(strict_types = 1);

namespace app\modules\graphql;

use app\models\sys\users\Users;
use cusodede\jwt\JwtHttpBearerAuth;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use pozitronik\traits\traits\ModuleTrait;
use yii\base\Module;
use Yii;

/**
 * Class GraphqlModule
 * @package app\modules\graphql
 */
class GraphqlModule extends Module
{
	use ModuleTrait;

	/**
	 * {@inheritdoc}
	 */
	public function init(): void
	{
		parent::init();
		Yii::$container->set(JwtHttpBearerAuth::class, [
			'jwtOptionsCallback' => static function(Users $user) {
				return [
					'validationConstraints' => [
						new SignedWith(Yii::$app->jwt->signer, Yii::$app->jwt->signerKey),
						LooseValidAt::class
					]
				];
			}
		]);
	}
}
