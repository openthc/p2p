# Install

These instructions are for Debian 9 and assume that operating the p2p node is the **ONLY** task for this system.


## Requirements

	apt-get -qqy update
	apt-get -qqy upgrade
	apt-get -qqy install dnsutils openntpd redis-server sqlite3
	apt-get -qqy install libapache2-mod-php
	apt-get -qqy install php-cli php-curl php-dev php-gd php-gnupg php-mbstring php-mcrypt php-redis php-zip

	# You may want:
	apt-get -qqy install php-geoip php-mysql php-pgsql php-ssh2 php-xdebug

This application, or at least parts of it, depend on Keybase, see https://keybase.io/docs/the_app/install_linux for details.


## Installation

	mkdir /opt/p2p.YOURHOST.TLD && cd /opt/p2p.YOURHOST.TLD
	git clone ./
	composer update

You may need to install composer (https://getcomposer.org/)


## Configuration

This assumes that the host will ONLY run this service.
You may need to adjust this configuration as necessary (or for a different web-server)

	cp ./etc/apache2.conf /etc/apache2/apache2.conf
	sed -i 's|@APP_ROOT@|/opt/*YOURPATH*|g' /etc/apache2/apache2.conf
	sed -i 's|@APP_HOST@|p2p.*YOURHOST.TLD*|g' /etc/apache2/apache2.conf
	/etc/init.d/apache2 restart

You will need to update the SSL configuration, either by placing your certificates in the default locations or by updating the web-server configuration.


## Checking

You can test your own host by issuing

	curl https://p2p.YOURHOST.TLD/index.html
	curl https://p2p.YOURHOST.TLD/robots.txt
	curl https://p2p.YOURHOST.TLD/network
