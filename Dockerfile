FROM ubuntu:16.04
# Install dependencies

RUN apt-get update
#RUN apt-get upgrade -y
RUN apt-get install -y software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

RUN apt-get install -y apache2

#RUN apt-get install -y zip unzip
RUN apt-get update
RUN apt-get install -y php7.2
RUN apt-get install php7.2-mysql
#RUN apt-get install php7.2-gd
RUN apt-get install php7.2-mbstring
#RUN apt-get install php7.2-curl

RUN apt-get update && apt-get install -yq --no-install-recommends php7.2-xml

RUN apt-get -y install git

# copy all file from current folder to /var/www/html/
COPY ./web/. /var/www/html/
COPY ./web/.htaccess /var/www/html/

# TODO: deploy in reality
#COPY ./resourcebooking.conf /etc/apache2/sites-available/resourcebooking.conf
#COPY ./apache2.conf /etc/apache2/apache2.conf
#RUN rm -rfv /etc/apache2/sites-enabled/*.conf
#RUN ln -s /etc/apache2/sites-available/resourcebooking.conf /etc/apache2/sites-enabled/resourcebooking.conf

# Current Development Env
# to see live logs we do : docker logs -f [CONTAINER ID]
# without the following line we get "AH00558: apache2: Could not reliably determine the server's fully qualified domain name"
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
# autorise .htaccess files
#RUN echo "<Directory \/var\/www\/html\/>\n" >> /etc/apache2/apache2.conf
#RUN echo "Options Indexes FollowSymLinks\n" >> /etc/apache2/apache2.conf
#RUN echo "AllowOverride all\n" >> /etc/apache2/apache2.conf
#RUN echo "Require all granted\n" >> /etc/apache2/apache2.conf
#RUN echo "</Directory>\n" >> /etc/apache2/apache2.conf

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configure apache
RUN phpenmod pdo_mysql
RUN a2enmod rewrite
RUN a2enmod headers

RUN chown -R www-data:www-data /var/www/html/
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_PID_FILE  /var/run/apache2/apache2.pid
ENV APACHE_RUN_DIR   /var/run/apache2
ENV APACHE_LOCK_DIR  /var/lock/apache2
ENV APACHE_LOG_DIR   /var/log/apache2

RUN mkdir -p $APACHE_RUN_DIR
RUN mkdir -p $APACHE_LOCK_DIR
RUN mkdir -p $APACHE_LOG_DIR


# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/apache2/access.log \ 
	&& ln -sf /dev/stderr /var/log/apache2/error.log

EXPOSE 80

CMD ["/usr/sbin/apache2", "-D",  "FOREGROUND"]