# Для начала указываем исходный образ, он будет использован как основа
FROM mysql:latest

# RUN выполняет идущую за ней команду в контексте нашего образа.
# В данном случае мы установим некоторые зависимости и модули PHP.
# Для установки модулей используем команду docker-php-ext-install. libblkid-dev e2fslibs-dev libboost-all-dev libaudit-dev
# На каждый RUN создается новый слой в образе, поэтому рекомендуется объединять команды.   ncurses-devel gcc-c++ boost boost-devel mysql-devel mysql-lib 
#
RUN apt-get update && apt-get dist-upgrade -y && apt-get install -y wget gnupg2 curl lsb-release nano systemd procps coreutils iproute2 git cmake unzip bzip2 libblkid-dev e2fslibs-dev libboost-all-dev libaudit-dev cpp g++ libncurses5-dev && \
	cd /etc/mysql/ && \
	mkdir custom.conf.d && \
	echo "!includedir /etc/mysql/custom.conf.d/" >> my.cnf

ADD my.cnf /etc/mysql/custom.conf.d/my.cnf

RUN chmod 0644 /etc/mysql/custom.conf.d/my.cnf