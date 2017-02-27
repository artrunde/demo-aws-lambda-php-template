#!/usr/bin/env bash
sudo su
yum install -y libexif-devel libjpeg-devel gd-devel curl-devel openssl-devel libxml2-devel gcc
cd /tmp
wget http://de1.php.net/get/php-7.1.2.tar.gz/from/this/mirror -O php-7.1.2.tar.gz
tar zxvf php-7.1.2.tar.gz
cd php-7.1.2
./configure --prefix=/tmp/php-7.1.2/compiled/ --without-pear --enable-shared=no --enable-static=yes --enable-phar --enable-json --disable-all --with-openssl --with-curl --enable-libxml --enable-simplexml --enable-xml --with-mhash --with-gd --enable-exif --with-freetype-dir --enable-mbstring --enable-sockets --enable-pdo --with-pdo-mysql --enable-tokenizer
make
make install
