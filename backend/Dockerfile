# base docker registry php 8 apache
FROM registry.neuron.id/pub/php:php8-latest

# main application directory (docroot)
WORKDIR /app

# enable ssl module and enable the default-ssl site
RUN apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install -y ssl-cert \
 && rm -r /var/lib/apt/lists/*

RUN a2enmod ssl \
 && a2ensite default-ssl

# replace docroot di config apache dari /var/www/html ke /app/public
RUN sed -ri -e 's!/var/www/html!/app/web!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/app/web!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -s -i -e "s/80/8080/" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# enable mod headers dan mod rewrite di apache
RUN a2enmod headers rewrite

# copy file aplikasi ke docroot
COPY ./backend/ ./

# change owner supaya bisa di write oleh aplikasi
RUN chown -Rc www-data:root . && \
    chmod -R g+rwX .

# open port 8080 dan 443 agar aplikasi bisa diakses (http dan https)
EXPOSE 8080 443
