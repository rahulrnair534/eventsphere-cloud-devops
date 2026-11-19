FROM php:8.3-fpm-alpine
RUN docker-php-ext-install pdo_mysql \
 && printf '[www]\nclear_env = no\npm = ondemand\npm.max_children = 5\n' > /usr/local/etc/php-fpm.d/zz-app.conf
WORKDIR /var/www/html
COPY . .
RUN mkdir -p uploads && chown -R www-data:www-data uploads
