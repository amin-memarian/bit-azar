#!/bin/sh

cd /var/www

composer require jenssegers/mongodb

# php artisan migrate:fresh --seed
php artisan cache:clear
#php artisan migrate
php artisan storage:link

/usr/bin/supervisord -c /etc/supervisord.conf
