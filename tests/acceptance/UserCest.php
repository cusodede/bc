<?php
use app\models\sys\users\Users;

class UserCest
{
    public function _before(AcceptanceTester $I)
    {
    	#создаем пользователя admin
    	$user = new Users();
    	$user->login = 'admin';
    	$user->name = 'admin';
    	$user->surname = 'admin';
    	$user->password = 'Admin1@3';
    	$user->salt = '14bac29074ae3aa2f987ac59432af20b85687f54';
    	$user->email = 'admin@admin.ru';
    	if (!$user->save()) {
    		throw new \yii\db\Exception(print_r($user->errors,1));
		}
    }

    /*
     * auth
     */
    public function authTest(AcceptanceTester $I, $params)
    {
    	$selectors = [
    		'loginButton' => 'button[name=login-button]',
			'passwordError' => 'input#loginform-password.is-invalid',
			'login' => 'LoginForm[login]',
			'password' => 'LoginForm[password]',
		];

		#без пароля
    	$I->amOnPage('/site/login');
    	$I->canSee('Логин');
		$I->fillField($selectors['login'], 'admin');
		$I->click($selectors['loginButton']);
		$I->wait(1);
		$I->seeElement($selectors['passwordError']);

		#кривой пароль
		$I->fillField($selectors['password'], 'admin');
		$I->click($selectors['loginButton']);
		$I->wait(1);
		$I->seeElement($selectors['passwordError']);

		#успех
		$I->fillField($selectors['login'], 'admin');
		$I->fillField($selectors['password'], 'Admin1@3');
		$I->click($selectors['loginButton']);
		$I->wait(2);
		$I->see('admin@admin.ru');
		$I->amOnPage('/site/logout');
		$I->wait(1);
	}

}
