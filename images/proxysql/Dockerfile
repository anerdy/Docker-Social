FROM proxysql/proxysql:2.0.13

RUN sed -i 's/^deb/# deb/' /etc/apt/sources.list.d/proxysql.list \
    && apt-get update \
    && apt-get upgrade --only-upgrade -y ca-certificates \
    && sed -i 's/^# deb/deb/' /etc/apt/sources.list.d/proxysql.list \
    && apt-get update

# Update packages
RUN apt update -y --fix-missing

# Install common packages
RUN apt install -y mysql-client

# Set custom config
ADD proxysql.cnf /etc/


#FROM debian:11
# Update packages
#RUN apt-get update && apt-get install -y wget lsb-release gnupg apt-transport-https ca-certificates mysql-client && \
#    wget -O - 'https://repo.proxysql.com/ProxySQL/repo_pub_key' | apt-key add - && \
#    echo deb https://repo.proxysql.com/ProxySQL/proxysql-2.0.x/$(lsb_release -sc)/ ./ | tee /etc/apt/sources.list.d/proxysql.list && \
#    apt-get update && apt-get install proxysql=2.0.13 && rm -rf /var/lib/apt/lists/*
#
## RUN apt-get update && apt-get dist-upgrade -y && apt install -y mysql-client
#
## Set custom config
#ADD proxysql.cnf /etc/
