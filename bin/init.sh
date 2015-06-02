export DEBIAN_FRONTEND=noninteractive

# Log file sice we're redirecting clean output to the console
log=/tmp/anonymart.log

echo "[+] Install log in $log"

# Updates
echo "[+] Updating the system"
apt-get update -y > $log
apt-get upgrade -y >> $log

# These services are preinstalled and not needed and even harmful since they're enabled by default
echo "[+] Removing useless packages"
apt-get remove --purge rsyslog postfix sendmail wget exim4 exim4-base exim4-config exim4-daemon-light -y >> $log

# nginx, php-fpm, mysql stack (plus waf, auto updates)
echo "[+] Installing required packages"
apt-get -y install nginx-naxsi curl php5 php5-fpm mysql-server php5-mysql php5-cli php5-mcrypt php5-curl php5-gd php5-gmp tor unattended-upgrades -qq >> $log

# On low RAM VPS composer ron out of memory and installs fails. Adding swap solve the problem
echo "[+] Cheking if swap is needed"
if [ $(free | awk '/^Mem:/{print $2}') -lt 900000 ]; then
	echo "[+] Adding swap"
	swap=true
   	dd if=/dev/zero of=/var/swap.temp bs=1M count=1024 >> $log
   	mkswap /var/swap.temp >> $log
   	swapon /var/swap.temp >> $log
fi

# Configuring auto updates
echo "[+] Configuring all the stuff"
cp /var/www/anonymart/configs/10periodic /etc/apt/apt.conf.d/10periodic >> $log
cp /var/www/anonymart/configs/10periodic /etc/apt/apt.conf.d/50unattended-upgrades >> $log

# PHP Functions which are not required and may be risky
disable_functions="disable_functions = pcntl_alarm, pcntl_fork, pcntl_waitpid, pcntl_wait, pcntl_wifexited, pcntl_wifstopped, pcntl_wifsignaled, pcntl_wexitstatus, pcntl_wtermsig, pcntl_wstopsig, pcntl_signal, pcntl_signal_dispatch, pcntl_get_last_error, pcntl_strerror, pcntl_sigprocmask, pcntl_sigwaitinfo, pcntl_sigtimedwait, pcntl_exec, pcntl_getpriority, pcntl_setpriority, define_syslog_variables, escapeshellarg, escapeshellcmd, eval, exec, fp, fput, ftp_connect, ftp_exec, ftp_get, ftp_login, ftp_nb_fput, ftp_put, ftp_raw, ftp_rawlist, highlight_file, ini_alter, ini_get_all, ini_restore, inject_code, mysql_pconnect, openlog, passthru, php_uname, popen, posix_getpwuid, posix_kill, posix_mkfifo, posix_setpgid, posix_setsid, posix_setuid, posix_setuid, posix_uname, proc_close, proc_get_status, proc_nice, proc_open, proc_terminate, shell_exec, syslog, system, xmlrpc_entity_decode"

# Required for php-fpm
echo cgi.fix_pathinfo=0 >> /etc/php5/fpm/php.ini
# open_basedir prevent php files from accessing the filesystem
echo "open_basedir = /var/www/anonymart:/tmp" >> /etc/php5/fpm/php.ini
sed -i "s/^disable_functions.*/$disable_functions/" /etc/php5/fpm/php.ini

echo listen = /var/run/php5-fpm.sock >> /etc/php5/fpm/pool.d/www.conf

echo "[+] Creating the database"
chown -R mysql:mysql /var/lib/mysql >> $log
mysql -uroot -e "CREATE DATABASE anonymart;" 2>> $log

echo "[+] Installing the PHP dependencies"
curl -sS https://getcomposer.org/installer | php >> $log
mv composer.phar /usr/local/bin/composer >> $log
composer config -g github-oauth.github.com 48c6aa57bbdb1e622e1c5807169338c2943c03f3 >> $log

# Enabling mcrypt extension
php5enmod mcrypt >> $log

service php5-fpm restart >> $log

cd /var/www/anonymart/
git config core.fileMode false
composer update
php /var/www/anonymart/artisan migrate --force

cp /var/www/anonymart/configs/rates.json /var/www/anonymart/data/rates.json
php /var/www/anonymart/artisan app:update-rates

echo "[+] Configuring Tor"
# Copying tor configuration
cp /var/www/anonymart/configs/torrc /etc/tor/torrc
service tor stop >> $log
service tor start >> $log
# Waiting for tor to start properly
sleep 30

# Saving tor onion address in a var
hostname=`cat /var/lib/tor/anonymart/hostname`

# Configuring nginx and naxsi
cp /var/www/anonymart/configs/nginx.default /etc/nginx/sites-available/anonymart
sed -i "s/ONIONHOSTNAME/$hostname/g" /etc/nginx/sites-available/anonymart
sed -i 's/#include \/etc\/nginx\/naxsi_core.rules;/include \/etc\/nginx\/naxsi_core.rules;/g' /etc/nginx/nginx.conf
ln -s /etc/nginx/sites-available/anonymart /etc/nginx/sites-enabled/anonymart >> $log
service nginx restart >> $log

# Forcing correct permissions
echo "[+] Fixing some permissions"
chown -R www-data:www-data /var/www/anonymart/
chown -R www-data:www-data /var/www/anonymart/data
chown -R www-data:www-data /var/www/anonymart/app/storage
chmod u+rwx /var/www/anonymart/bin/*

crontab /var/www/anonymart/configs/cron &>> $log

update-rc.d php5-fpm defaults >> $log
update-rc.d mysql defaults >> $log
update-rc.d nginx defaults >> $log
update-rc.d tor defaults >> $log

echo "[+] Enforcing system wide tor"
echo "127.0.0.1" > /etc/resolv.conf >> $log
chattr +i /etc/resolv.conf >> $log

# Directly from https://trac.torproject.org/projects/tor/wiki/doc/TransparentProxy#LocalRedirectionThroughTor

#destinations you don't want routed through Tor
_non_tor="192.168.1.0/24 192.168.0.0/24 10.0.0.0/8"

#the UID that Tor runs as (varies from system to system)
_tor_uid="106"

#Tor's TransPort
_trans_port="9040"

### flush iptables
iptables -F
iptables -t nat -F

### set iptables *nat
iptables -t nat -A OUTPUT -m owner --uid-owner $_tor_uid -j RETURN
iptables -t nat -A OUTPUT -p udp --dport 53 -j REDIRECT --to-ports 53

#allow clearnet access for hosts in $_non_tor
for _clearnet in $_non_tor 127.0.0.0/9 127.128.0.0/10; do
   iptables -t nat -A OUTPUT -d $_clearnet -j RETURN
done

#redirect all other output to Tor's TransPort
iptables -t nat -A OUTPUT -p tcp --syn -j REDIRECT --to-ports $_trans_port

### set iptables *filter
iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

#allow clearnet access for hosts in $_non_tor
for _clearnet in $_non_tor 127.0.0.0/8; do
   iptables -A OUTPUT -d $_clearnet -j ACCEPT
done

#allow only Tor output
iptables -A OUTPUT -m owner --uid-owner $_tor_uid -j ACCEPT
iptables -A OUTPUT -j REJECT

if [ $swap=true ]; then
	echo "[+] Disabling and removing swap"
   	swapoff /var/swap.temp
   	rm /var/swap.temp
fi

echo "[+] Your onion address: "$hostname