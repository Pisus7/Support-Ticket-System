### Da die .env, app key und dependencies nicht commitet werden muss mein Partner Pius diese Befehle ausführen, um die Anwendung zum Laufen zu bringen:

git clone ...

cd project

cp .env.example .env

composer install

php artisan key:generate

php artisan migrate