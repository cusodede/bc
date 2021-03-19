#Установка
~~~
git clone https://github.com/pozitronik/bc.git
composer install
yii migrate --migrationPath=@vendor/pozitronik/yii2-users-options/src/migrations
yii migrate --migrationPath=@vendor/pozitronik/yii2-options/src/migrations
yii migrate
~~~
  