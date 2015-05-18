apt-get install git -y
git clone https://github.com/anonymart/anonymart.git /var/www/anonymart -b $1 --single-branch
chmod u+rwx /var/www/anonymart/bin/init.sh
/var/www/anonymart/bin/init.sh