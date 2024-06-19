
# Usa a imagem base do Ubuntu 20.04
FROM ubuntu:20.04

# Define o mantenedor
LABEL maintainer="yourgithub@email.com"

# Atualiza o sistema e instala dependências necessárias
RUN apt-get update && apt-get install -y sudo

# Instala openssh-server, make, unzip, ghostscript
RUN sudo apt-get install -y openssh-server make unzip ghostscript

# Instala git
RUN apt-get install -y git

# Configurações globais do git
RUN git config --global user.name "Your github name" && \
    git config --global user.email "yourgithub@email.com"

# Atualiza o sistema novamente
RUN sudo apt update

# Instala o Apache
RUN sudo apt install -y apache2

# Instala o MySQL
RUN sudo apt install -y mysql-server

# Inicia o serviço MySQL
RUN sudo service mysql start

# Configura MySQL
#RUN sudo mysql_secure_installation <<< "y 0 y n n y" 

#RUN sudo mysql -e "CREATE USER 'proethos2'@'localhost' IDENTIFIED BY 'Proethos@2';" && \
  #  sudo mysql -e "CREATE DATABASE proethos2;" && \
   # sudo mysql -e "GRANT ALL PRIVILEGES ON proethos2.* to 'proethos2'@'localhost';"

# Inicia o serviço MySQL e configura MySQL
RUN sudo service mysql start && \
    sudo mysql -e "UPDATE mysql.user SET plugin = 'mysql_native_password' WHERE User = 'root';" && \
    sudo mysql -e "FLUSH PRIVILEGES;" && \
    sudo mysql -e "DELETE FROM mysql.user WHERE User='';" && \
    sudo mysql -e "DROP DATABASE IF EXISTS test;" && \
    sudo mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';" && \
    sudo mysql -e "FLUSH PRIVILEGES;" && \
    sudo mysql -e "CREATE USER 'proethos2'@'localhost' IDENTIFIED BY 'Proethos@2';" && \
    sudo mysql -e "CREATE DATABASE proethos2;" && \
    sudo mysql -e "GRANT ALL PRIVILEGES ON proethos2.* TO 'proethos2'@'localhost';" && \
    sudo mysql -e "FLUSH PRIVILEGES;"

# Instala dependências PHP
RUN sudo apt update && \
    sudo apt install -y libapache2-mod-php php-mysql php-gd phpunit php-curl

# Instala Composer
RUN apt-get install -y composer && \
    apt-get install -y curl && \
    cd ~ && curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
    HASH=`curl -sS https://composer.github.io/installer.sig` && \
    php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer


# Instala wkhtmltopdf
RUN cd ~ && wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.focal_amd64.deb && \
    sudo apt install -y ./wkhtmltox_0.12.6-1.focal_amd64.deb

# Configura permissões
RUN sudo chmod o+r /root/wkhtmltox_0.12.6-1.focal_amd64.deb 

#sudo php vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php



# Clona o repositório ProEthos2
RUN mkdir -p /var/www/html/proethos2 && \
    cd /var/www/html/proethos2 && \
    git clone https://github.com/bireme/proethos2.git proethos2

# Gera chaves para ProEthos2
RUN php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES)) . PHP_EOL;' > /root/private_key && \
    php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES)) . PHP_EOL;' > /root/index_key


# Atualiza e instala dependências com Composer
RUN sudo composer self-update 2.2.9 
RUN cd /var/www/html/proethos2/proethos2/symphony/

RUN ls -la /var/www/html/proethos2/proethos2/symphony/
RUN sudo chmod o+r /var/www/html/proethos2/proethos2/symphony/


#######################

COPY composer.json /var/www/html/proethos2/proethos2/symphony/composer.json

#ENV COMPOSER_ALLOW_SUPERUSER = 1
#COPY composer.json /var/www/html/proethos2/proethos2/symphony/composer.json
#RUN export COMPOSER_ALLOW_SUPERUSER=1; 

#RUN sudo chgrp www-data -R /var/www/html/proethos2/proethos2/symphony
#RUN chmod -R 0775 /var/www/html/proethos2/proethos2/symphony
#RUN chmod -R 0775 /var/www/html/proethos2/proethos2/symphony

##############################


#CMD bash -c "composer install"

#RUN cd /var/www/html/proethos2/proethos2/symphony/


#RUN set -eux; \
 #   { \
  #      composer install --optimize-autoloader --dev && \
   #     exit 0; \
   # } || { \
    #    echo "Ocorreu um erro durante a instalação das dependências do Composer."; \
     #   exit 0; \
    #}

#RUN composer install --optimize-autoloader --dev 2>/dev/null || true


# Corrige Doctrine ORM
 #CMD bash -c "cd /var/www/html/proethos2/proethos2/tools && \
 #  chmod a+x fix-doctrine-orm.sh && ./fix-doctrine-orm.sh"




# Instala dependências Symfony
#RUN cd /var/www/html/proethos2/proethos2/symphony && \
 #composer install

 CMD bash -c "sudo php vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php"


#RUN cd ../symphony && \
#composer install || true



#CMD BASH -c "cd var/www/html/proethos2/proethos2/symphony/"
 #CMD bash -c "make load_initial"





COPY parameters.yml /var/www/html/proethos2/proethos2/symphony/app/config/parameters.yml
COPY proethos.conf /etc/apache2/sites-available/proethos2.conf


COPY confi.sh configurar.sh
RUN chmod +x /configurar.sh
#ENTRYPOINT ["/entrypoint.sh"]



# Exponha a porta 80
EXPOSE 80

# Comando para iniciar o Apache e o MySQL
CMD service apache2 start && service mysql start && bash
