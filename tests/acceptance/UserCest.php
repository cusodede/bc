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
    public function authTest(AcceptanceTester $I)
    {
		#без пароля
    	$I->amOnPage('/site/login');
    	$I->canSee('Логин');
		$I->fillField('LoginForm[login]', 'admin');
		$I->click("button[name=login-button]");
		$I->wait(1);
		$I->seeElement('input#loginform-password.is-invalid');

		#кривой пароль
		$I->fillField('LoginForm[password]', 'admin');
		$I->click("button[name=login-button]");
		$I->wait(1);
		$I->seeElement('input#loginform-password.is-invalid');

		#успех
		$I->fillField('LoginForm[login]', 'admin');
		$I->fillField('LoginForm[password]', 'Admin1@3');
		$I->click("button[name=login-button]");
		$I->wait(2);
		$I->see('admin@admin.ru');
		$I->amOnPage('/site/logout');
		$I->wait(1);
	}

}
