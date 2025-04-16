FROM 192.168.10.20:8083/kando/php:8.1-fpm

# Set working directory
WORKDIR /var/www

# Copy code to /var/www
COPY --chown=www-data:www-data . /var/www

# add root to www group
RUN chmod -R 777 /var/www/storage

# Copy nginx/php/supervisor configs
RUN cp docker/supervisor.conf /etc/supervisord.conf
RUN cp docker/php.ini /usr/local/etc/php/conf.d/app.ini
RUN cp docker/nginx.conf /etc/nginx/sites-enabled/default

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Deployment steps
RUN composer update
RUN composer require laravel/telescope
RUN composer install --optimize-autoloader --no-dev
RUN composer require jenssegers/mongodb
RUN composer require laravel/passport
RUN php artisan passport:install
RUN composer require shetabit/payment
RUN composer require linwj/coinex
RUN composer require pragmarx/google2fa-laravel
RUN composer require bacon/bacon-qr-code
RUN composer require predis/predis
RUN php artisan optimize
RUN php artisan schedule:run
RUN chmod -R 777 /var/www/
RUN chmod +x /var/www/docker/run.sh
RUN /usr/bin/find /usr/local/etc -type f -name newrelic.ini -exec sed -i -e "s/NRAK-A8Z07KSWTBECROMZLHMBYY5H5I1/6c65a18382d34d66b336be2e7dc39a6aFFFFNRAL/" -e "s/newrelic.appname[[:space:]]=[[:space:]].*/newrelic.appname = \"bitapi-dev\"/" {} \; 2>/dev/null

EXPOSE 80
ENTRYPOINT ["/var/www/docker/run.sh"]
