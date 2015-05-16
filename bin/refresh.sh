php $(dirname $0)/../artisan migrate
php $(dirname $0)/../artisan migrate:refresh
rm $(dirname $0)/../data/*
php $(dirname $0)/../artisan app:update-rates