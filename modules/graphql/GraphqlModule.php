<?php
declare(strict_types = 1);

namespace app\modules\graphql;

use app\models\sys\users\Users;
use cusodede\jwt\JwtHttpBearerAuth;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use pozitronik\traits\traits\ModuleTrait;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\console\Application as ConsoleApplication;

/**
 * Class GraphqlModule
 * @package app\modules\graphql
 */
class GraphqlModule extends Module implements BootstrapInterface {
	use ModuleTrait;

    /**
     * @return void
     */
    public function init():void {
		parent::init();

		Yii::$container->set(JwtHttpBearerAuth::class, [
			'jwtOptionsCallback' => static function(Users $user) {
				return [
					'validationConstraints' => [
						new SignedWith(
							Yii::$app->jwt->signer,
							empty(Yii::$app->jwt->verifyKey)?Yii::$app->jwt->signerKey:Yii::$app->jwt->verifyKey
						),
						LooseValidAt::class
					]
				];
			}
		]);
	}

	/**
	 * @param Application $app
	 * @return void
	 */
	public function bootstrap($app):void {
		if ($app instanceof ConsoleApplication) {
			$this->controllerNamespace = 'app\modules\graphql\commands';
		}
	}
}
