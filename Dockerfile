FROM registry.neuron.id/pub/php:php8-latest

WORKDIR /data/custprofile

# replace docroot di config apache dari /var/www/html ke /app/public
RUN sed -ri -e 's!/var/www/html!/data/custprofile/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/data/custprofile/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -s -i -e "s/80/8080/" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# enable mod headers dan mod rewrite di apache
RUN a2enmod headers rewrite

# copy APP
COPY . ./
COPY .env.example ./.env
COPY ./vendor ./
RUN chown -R www-data. ./storage

# open port 8080 agar aplikasi bisa diakses (http)
EXPOSE 8080
