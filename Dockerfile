# http://mercurial.selenic.com/wiki/HgWebDirStepByStep#Directory_Structure

FROM ubuntu:14.04
MAINTAINER Alex McLain <alex@alexmclain.com>

RUN apt-get -qq update
RUN apt-get -y install apache2 apache2-utils curl mercurial php5 php5-cli php5-mcrypt

# TODO: Remove
RUN apt-get -y install vim
RUN echo "colorscheme delek" > ~/.vimrc

# Configure hgweb
ADD hg/add.php      /etc/default/hgweb/hg/
ADD hg/hgweb.config /etc/default/hgweb/hg/
ADD hg/hgweb.cgi    /etc/default/hgweb/hg/
ADD hg/hgusers      /etc/default/hgweb/hg/

# Configure Apache
ADD apache/hg.conf /etc/default/hgweb/apache/
RUN rm /etc/apache2/sites-enabled/*
RUN a2enmod rewrite && a2enmod cgi

ADD load-default-scripts /bin/
RUN chmod u+x /bin/load-default-scripts

VOLUME /var/hg
VOLUME /etc/apache2/sites-available
EXPOSE 80

CMD load-default-scripts && service apache2 start && /bin/bash