FROM debian:jessie

EXPOSE 9001

RUN apt-get install git -y
RUN git clone https://github.com/anonymart/anonymart.git /var/www/anonymart --single-branch
RUN chmod u+rwx /var/www/anonymart/bin/init.sh
RUN /var/www/anonymart/bin/init.sh