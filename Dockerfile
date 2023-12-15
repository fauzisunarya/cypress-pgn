FROM hub.pgn.co.id/base/php8:latest

WORKDIR /data/cms

# replace docroot di config apache dari /var/www/html ke /app/public
RUN sed -ri -e 's!/var/www/html!/data/cms/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/data/cms/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -s -i -e "s/80/8080/" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# enable mod headers dan mod rewrite di apache
RUN a2enmod headers rewrite

# config apm
RUN echo "elastic_apm.enabled = "\"$"{APM_ACTIVE}\"" >> /opt/elastic/apm-agent-php/etc/elastic-apm-custom.ini
RUN echo "elastic_apm.api_key = "\"$"{APM_SECRETTOKEN}\"" >> /opt/elastic/apm-agent-php/etc/elastic-apm-custom.ini
RUN echo "elastic_apm.environment = "\"$"{APM_ENVIRONMENT}\"" >> /opt/elastic/apm-agent-php/etc/elastic-apm-custom.ini
RUN echo "elastic_apm.server_url = "\"$"{APM_SERVERURL}\"" >> /opt/elastic/apm-agent-php/etc/elastic-apm-custom.ini
RUN echo "elastic_apm.service_name = "\"$"{APM_APPNAME}\"" >> /opt/elastic/apm-agent-php/etc/elastic-apm-custom.ini

# copy APP
COPY . ./
COPY .env.example ./.env
COPY ./vendor ./vendor

RUN chown -R www-data. ./storage ./bootstrap/cache

# open port 8080 agar aplikasi bisa diakses (http)
EXPOSE 8080
