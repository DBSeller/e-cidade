FROM httpd:alpine

COPY ./httpd.conf /usr/local/apache2/conf/httpd.conf
RUN mkdir -p /var/www/html

CMD ["httpd-foreground"]
