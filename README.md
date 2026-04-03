composer install
rename .env.example to .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
