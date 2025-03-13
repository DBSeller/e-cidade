FROM registry.dbseller.com.br:5000/php:5.3.29-fpm

RUN echo 'Acquire::http::Proxy "http://proxy.dbseller.com.br:3142";' > /etc/apt/apt.conf.d/01proxy && \
  echo 'APT::Get::AllowUnauthenticated "1";' >> /etc/apt/apt.conf.d/01proxy

RUN apt-get update && \
  apt-get install -y libpq-dev libxml2-dev libcurl4-openssl-dev libmcrypt-dev curl libbz2-dev libpng-dev libfreetype6-dev libgd2-xpm-dev

RUN docker-php-ext-install pdo \
  pdo_pgsql \
  pgsql \
  zip \
  soap \
  bcmath \
  bz2 \
  gd \
  json \
  mbstring \
  mcrypt \
  xml \
  xmlrpc

RUN echo "register_globals = On\n"\
  "register_long_arrays = On\n"\
  "magic_quotes_gpc = On\n"\
  "allow_call_time_pass_reference = On\n"\
  "[Session]\n"\
  "session.save_path = \"/var/lib/php/sessions\""\ >> /usr/local/etc/php/php.ini

RUN mkdir -p /var/lib/php/sessions
RUN chmod 777 /var/lib/php/sessions
