cd $(dirname $0)/../
git pull
composer update
php artisan migrate --force
crontab /var/www/anonymart/configs/cron
