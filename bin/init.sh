export DEBIAN_FRONTEND=noninteractive

echo deb http://deb.torproject.org/torproject.org wheezy main >> /etc/apt/sources.list

apt-get update -y 
apt-get upgrade -y
apt-get remove --purge rsyslog exim postfix sendmail wget -y
apt-get -y install nginx curl php5 php5-fpm mysql-server php5-mysql php5-cli php5-mcrypt php5-curl php5-gd php5-gmp tor ufw fail2ban unattended-upgrades -qq

ufw allow ssh
ufw enable

cp /var/www/anonymart/configs/10periodic /etc/apt/apt.conf.d/10periodic
cp /var/www/anonymart/configs/10periodic /etc/apt/apt.conf.d/50unattended-upgrades

echo cgi.fix_pathinfo=0 >> /etc/php5/fpm/php.ini
echo listen = /var/run/php5-fpm.sock >> /etc/php5/fpm/pool.d/www.conf

chown -R mysql:mysql /var/lib/mysql
mysql -uroot -e "CREATE DATABASE anonymart;"

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer config -g github-oauth.github.com 48c6aa57bbdb1e622e1c5807169338c2943c03f3

php5enmod mcrypt
service nginx restart
service php5-fpm restart

cd /var/www/anonymart/
git config core.fileMode false
composer update
php /var/www/anonymart/artisan migrate --force
#Update rates sometimes fails. Do 3 times just to be sure
php /var/www/anonymart/artisan app:update-rates
sleep 5
php /var/www/anonymart/artisan app:update-rates
sleep 5
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

chown -R www-data:www-data /var/www/anonymart/ 
chown -R www-data:www-data /var/www/anonymart/data
chown -R www-data:www-data /var/www/anonymart/app/storage
chmod u+rwx /var/www/anonymart/bin/*

/var/www/anonymart/bin/route.sh

crontab /var/www/anonymart/configs/cron
echo $hostname