FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli \
    && a2enmod rewrite

# Use PHP production defaults (hides raw errors from end users)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/assets

# Support dynamic PORT env var (used by Render, Koyeb, Railway, etc.)
CMD ["sh", "-c", "PORT=${PORT:-80}; sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && sed -i \"s/:80/:${PORT}/\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]
