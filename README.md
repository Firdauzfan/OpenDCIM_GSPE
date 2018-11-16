## Install DCIM:
1. Buka terminal.
2. command sudo apt-get install lamp-server^
3. ketik password dan Enter.
4. Tunggu installan selesai.
5. Install Dependencies sudo apt-get install php-snmp snmp-mibs-downloader php-curl php-gettext
graphviz
6. lalu cd /var/www dan sudo git clone https://github.com/Firdauzfan/OpenDCIM_GSPE.git
7. lalu sudo mv OpenDCIM_GSPE dcim
8. cd dcim
9 lalu sudo mkdir pictures dan sudo mkdir drawings
10. lalu sudo chgrp -R www-data /var/www/dcim/pictures /var/www/dcim/drawings
11. Lalu
sudo chmod 777 drawings/
sudo chmod 777 pictures/
sudo chmod 777 vendor/mpdf/mpdf/ttfontdata/
11. lalu sudo cp db.inc.php-dist db.inc.php

## Install Database DCIM:
1. mysql -u root -p
2. create database dcim;
grant all on dcim.* to 'dcim'@'localhost' identified by 'dcim';
flush privileges;
exit;

## Configure Apache2:
1. cd /etc/apache2/sites-available
2. sudo nano default-ssl.conf
3. Lalu tambahkan/ubah
    DocumentRoot /var/www/dcim
    <Directory "/var/www/dcim">

    Options All
    AllowOverride All
    AuthType Basic
    AuthName dcim
    AuthUserFile /var/www/dcim/.htpassword
    Require all granted
    </Directory>

## Membuat User Access:
1. sudo nano /var/www/dcim/.htaccess
2. Lalu tambahkan
AuthType Basic
AuthName "openDCIM"
AuthUserFile /var/www/opendcim.password
Require valid-user
3. Save dan lakukan command sudo htpasswd -cb /var/www/opendcim.password dcim dcim

## Enable Apache dan Site
sudo a2enmod ssl
sudo a2enmod rewrite
sudo a2ensite default-ssl
sudo service apache2 restart

## Import Database DCIM
1. buka phpmyadmin atau database manager
2. buat database dengan nama dcim
3 buka pada file /var/www/dcim/
4. Lalu import database dcim.sql
