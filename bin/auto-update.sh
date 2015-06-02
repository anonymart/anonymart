if grep -q '"do_auto_update":true' $(dirname $0)/../data/settings.json; then
	$(dirname $0)/update.sh
fi