FROM alpine:3.15
LABEL authors="Paulo Pastoriza <paulo.pastoriza@dbseller.com.br>"

RUN apk add --update --no-cache bash \
        curl \
        git \
        sshpass \
        openssh-client \
	vim \
	jq \
	sqlite \
        php7 \
        php5-bcmath \
        php5-calendar \
        php5-cli \
        php5-ctype \
        php5-curl \
        php7-dom \
        php7-gd \
        php7-iconv \
        php7-json \
        php7-mbstring \
        php7-openssl \
        php7-pdo \
        php7-pdo_pgsql \
	php7-pdo_sqlite \
        php7-pear \
        php7-pgsql \
        php7-phar \
        php7-soap \
        php7-xml \
        php7-xmlreader \
        php7-zip

RUN curl --silent --show-error --location https://getcomposer.org/installer | php7 -- --install-dir=/usr/bin --filename=composer && \
	chmod +x /usr/bin/composer

RUN curl --silent --show-error https://getcomposer.org/download/1.10.26/composer.phar -o /usr/bin/composer_v1 && \
	chmod +x /usr/bin/composer_v1

RUN curl --silent --show-error --location https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar -o /usr/bin/phpcs && \
    chmod +x /usr/bin/phpcs

RUN ln -s /usr/bin/php7 /usr/bin/php7.4

RUN rm -rf /var/cache/apk/*

ENV GIT_SSL_NO_VERIFY=1

CMD ["/bin/sh"]
