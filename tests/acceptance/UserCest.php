<?php

class UserCest
{
    public function _before(AcceptanceTester $I)
    {
//    	#создаем пользователя admin
//    	$user = new \app\models\sys\users\Users();
//    	$user->login = 'admin';
//    	$user->password = 'fc1fffdb3e0bbb71a6ad2fd0439a824490bd75b1';//Admin1@3
//    	$user->salt = '14bac29074ae3aa2f987ac59432af20b85687f54';
//    	$user->email = 'admin@admin.ru';
//    	$user->save();
    }

    /*
     * Registration
     */
	public function registrationTest(AcceptanceTester $I)
	{
		$I->amOnPage('/site/login');
		$I->click('Регистрация');
		$I->fillField('RegistrationForm[name]', 'admin');
		$I->fillField('RegistrationForm[surname]', 'admin');
		$I->fillField('RegistrationForm[login]', 'admin');
		$I->fillField('RegistrationForm[email]', 'admin@admin.ru');
		$I->fillField('RegistrationForm[password]', 'Admin1@3');
		$I->fillField('RegistrationForm[passwordRepeat]', 'Admin1@3');
		$I->click('Зарегистрироваться');
		$I->wait(3);

		$I->see('Восстановление пароля');
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
