if [ -z "$1" ]
then
	php $(dirname $0)/../artisan migrate
	php $(dirname $0)/../artisan migrate:refresh
	rm $(dirname $0)/../data/*
	php $(dirname $0)/../artisan app:update-rates
	echo "" > $(dirname $0)/../app/storage/logs/laravel.log
	nightwatch
else
	LS_ONION=$1 bash -c 'nightwatch'
fi