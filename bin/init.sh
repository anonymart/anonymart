export DEBIAN_FRONTEND=noninteractive

apt-get update -y 
apt-get upgrade -y
apt-get -y install python-qt4 python-pip sudo nginx curl php5 php5-fpm mysql-server php5-mysql php5-cli php5-mcrypt php5-curl php5-gd tor electrum -qq

echo cgi.fix_pathinfo=0 >> /etc/php5/fpm/php.ini
echo listen = /var/run/php5-fpm.sock >> /etc/php5/fpm/pool.d/www.conf

chown -R mysql:mysql /var/lib/mysql
mysql -uroot -e "CREATE DATABASE lemonade;"

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer config -g github-oauth.github.com 48c6aa57bbdb1e622e1c5807169338c2943c03f3

cp /var/www/anonymart/configs/env.php /var/www/anonymart/.env.php

chgrp -R www-data /var/www/anonymart
chown -R www-data:www-data /var/www/anonymart/
chown -R www-data:www-data /var/www/anonymart/app/storage/

php5enmod mcrypt
service nginx restart
service php5-fpm restart

cd /var/www/anonymart/
composer update
php /var/www/anonymart/artisan migrate --force
php /var/www/anonymart/artisan app:update-rates
cp /var/www/anonymart/configs/torrc /etc/tor/torrc
service tor stop
service tor start
tor &>/dev/null &
sleep 30

hostname=`cat /var/lib/tor/anonymart/hostname`

cp /var/www/anonymart/configs/nginx.default /etc/nginx/sites-available/anonymart
perl -pi -e "s/ONIONHOSTNAME/$hostname/g" /etc/nginx/sites-available/anonymart
ln -s /etc/nginx/sites-available/anonymart /etc/nginx/sites-enabled/anonymart
service nginx restart

crontab /var/www/anonymart/configs/cron

pip install https://download.electrum.org/Electrum-2.2.tar.gz
electrum setconfig auto_cycle True
electrum daemon start
echo www-data ALL = NOPASSWD: /usr/local/bin/electrum >> /etc/sudoers
php /var/www/anonymart/artisan app:create-electrum-wallet

echo $hostname