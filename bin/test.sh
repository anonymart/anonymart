if [ -z "$1" ]
then
    touch $(dirname $0)/../flags/isTesting
	$(dirname $0)/refresh.sh
	echo "" > $(dirname $0)/../app/storage/logs/laravel.log
	nightwatch
else
	LS_ONION=$1 bash -c 'nightwatch'
fi