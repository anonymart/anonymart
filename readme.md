#Anonymart

Anonymart is (attempting to be) a secure, anonymous storefront that someone without any programming background can deploy on their own server. Its built on Tor, Blockchain.info, and Laravel.

Anonymart is **not ready for production**. There are known, and unknown, security issues and missing features. Subscribe to [/r/anonymart](http://reddit.com/r/anonymart) for updates.

* [Clearnet Demo](https://7ssrx6tzhtfpx2m7.tor2web.org/)
* [Tor Demo](http://7ssrx6tzhtfpx2m7.onion/)
* [/r/anonymart](http://reddit.com/r/anonymart)
* [Installation Guide for Programmers](#installation-guide-for-programmers)
* [Installation Guide for Non-programmers](#installation-guide-for-non-programmers)
* [Hosting](#hosting)

####Installation Guides

###### Installation Guide For Programmers

These instructions assume you are working on a fresh installation of Debian 8 and running as root. They may work on other versions of Debian and Ubuntu.

    apt-get install git -y
    git clone https://github.com/anonymart/anonymart.git /var/www/anonymart
    chmod u+rwx /var/www/anonymart/bin/init.sh
    /var/www/anonymart/bin/init.sh

Let the installation run for a few minutes. The very last line of the installation script will output your onion url.

###### Installation Guide For Non-Programmers

1. Head over to Vultr.com and create an account (you might want to use Tor)
2. Click "Deploy New Instance"
3. Choose "Debian 8" as your operating system. For everything else, you can choose the defaults. 
4. Place your order.
5. When your server has finished installing, click "Manage" and then "View Console"
6. You will need to enter your username and password to log into the server. These credentials can be found in the "My Server" tab of your Server page.
7. Type in the following enter commands. After each command hit the enter key and wait until you see `root@vultr:~#` before entering the following command.
   1. `apt-get install git -y`
   2. `git clone https://github.com/anonymart/anonymart.git /var/www/anonymart`
   3. `mod u+rwx /var/www/anonymart/bin/init.s`
   4. `/var/www/anonymart/bin/init.sh`
8. Wait 5-10 minutes while your installation completes. The last line of output should be the url of your storefront on Tor. Enter that url in your Tor browser and complete the instructions there.

#### Hosting
I suggest hosting on Vultr. Its the only provider I'm aware of that offers Debian 8, root access, and a web-based shell console (ssh over Tor is tricky). If you know of any hosting providers that might be a good fit for Anonymart, please reach out and I'd be happy to add instructions.