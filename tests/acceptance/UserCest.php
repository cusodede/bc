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
//    	$user->password = 'fc1fffdb3e0bbb71a6ad2fd0439a824490bd75b1';//Admin1@3
    	$user->password = 'Admin1@3';//Admin1@3
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
		$I->click('Войти');
		$I->wait(1);
		$I->see('Необходимо заполнить «Пароль».');

		#кривой пароль
		$I->fillField('LoginForm[password]', 'admin');
		$I->click('Войти');
		$I->wait(1);
		$I->see('Неправильные логин или пароль.');

		#успех
		$I->fillField('LoginForm[login]', 'admin');
		$I->fillField('LoginForm[password]', 'Admin1@3');
		$I->click('Войти');
		$I->wait(2);
		$I->see('admin@admin.ru');
		$I->amOnPage('/site/logout');
		$I->wait(1);
	}

}
