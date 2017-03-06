#!/usr/bin/env bash
sudo su
yum install -y libexif-devel libjpeg-devel gd-devel curl-devel openssl-devel libxml2-devel gcc
cd /tmp
wget http://de1.php.net/get/php-7.0.16.tar.gz/from/this/mirror -O php-7.0.16.tar.gz
tar zxvf php-7.0.16.tar.gz
cd php-7.0.16
./configure --prefix=/tmp/php-7.0.16/compiled/ --disable-all --without-pear --enable-shared=no --enable-static=yes --enable-phar --enable-json --with-openssl --with-curl --enable-libxml --enable-simplexml --enable-xml --with-mhash --enable-exif --enable-mbstring --enable-sockets --enable-pdo --with-pdo-mysql --enable-tokenizer --enable-mbstring --with-gd --enable-gd-native-ttf --with-freetype-dir --with-jpeg-dir --with-png-dir --enable-ctype --enable-filter
make
make install
