touch $(dirname $0)/../flags/isTesting
$(dirname $0)/refresh.sh
echo "" > $(dirname $0)/../app/storage/logs/laravel.log
nightwatch
rm $(dirname $0)/../flags/isTesting
