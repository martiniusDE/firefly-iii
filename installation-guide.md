---
layout: page
title: Installation guide
permalink: /installation-guide/
---

# Normal installation

Have you seen the [system requirements](../system-requirements/)? If so, please continue!

## Introduction

The hardest part of this installation manual is installing Apache (or nginx), and PHP 7. Most Linux installations come with PHP 5 so you need to upgrade it.

Installing Firefly III itself is fairly easy, because composer and Laravel are easy to handle.

## 1. Prepare and upgrade your server.

Please pick either Ubuntu or CentOS below.

## 1a. Ubuntu

First, make sure your server is up to date:

```
sudo apt-get update; sudo apt-get upgrade
```

Then, install MySQL:

```
sudo apt-get install -y mysql-server
sudo mysql_install_db; sudo mysql_secure_installation
```

Ubuntu 16 and higher come with PHP 7. You're set! If you do not run Ubuntu 16 or higher, use these commands to install PHP 7:


```
sudo apt-get install -y language-pack-en-base
sudo LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

sudo apt-get update;sudo apt-get upgrade

sudo apt-get install -y php7.0 php7.0-cli php7.0-common php7.0-fpm php7.0-mysql php7.0-curl php7.0-gd php7.0-imap php7.0-intl php7.0-json php7.0-mcrypt php7.0-readline php7.0-tidy php7.0-zip php7.0-bcmath php7.0-xml php7.0-mbstring php7.0-sqlite php7.0-bz2

```

Either way, install composer by using the following command.


```
cd ~; wget https://getcomposer.org/download/1.0.2/composer.phar; chmod +x composer.phar; sudo mv composer.phar /usr/local/bin/composer; sudo composer selfupdate

```

## 1b. CentOS

To install the necessary PHP7 packages on CentOS, you can run the following code:

For the latest PHP7 version: 

```
rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm
rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-6.noarch.rpm
```

Install the programs needed:
```
yum install apache mysql mysql-server php70w php70w-mbstring php70w-mcrypt php70w-bcmath php70w-gd php70w-intl php70w-pdo php70w-xml php70w-mysql.x86_64
```

Install composer

```
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

This should get you up and running. Now, follow the "installation steps".

## 2. Installation steps

1. SSH into your server, or otherwise access it.
2. Go to the directory where you want to install Firefly III.
   - Please keep in mind that the web root of Firefly III is in the ``firefly-iii/public/`` directory, so you may need to update your web server configuration.
3. Once you're there, run the following command:
   - ``git clone https://github.com/JC5/firefly-iii.git --depth 1``
   - Or variants of this command:
   - ``git clone https://github.com/JC5/firefly-iii.git some-other-dir --depth 1``
   - ``git clone https://github.com/JC5/firefly-iii.git --depth 1`` (defaults to ``firefly-iii``)

This will download Firefly III and put it in the right place.

Then, configure Firefly III by doing the following:


``cp firefly-iii/.env.example firefly-iii/.env``

Open ``firefly-iii/.env``.

* Change the ``DB_*`` settings as you see fit.
* Update the ``MAIL_*`` settings as you see fit.
* If you want to track statistics, update the Google Analytics ID.
* Set ``SITE_OWNER`` to your own email address.

Once you've set this up, run the following commands:

* ``cd firefly-iii`` (or how you've named your folder)
* ``composer install --no-dev``
* ``php artisan migrate --seed --env=production``

Finally, make sure that the storage directories are writeable, _for example_ by using these commands:

* ``chown -R www-data:www-data storage``
* ``chmod -R g+w storage``

### Extra steps for Apache

If you are running Apache, please change **AllowOverride None** to ``AllowOverride All`` in your Apache configuration. The configuration for your Firefly III directory should look like this:

```
<Directory /var/www/firefly-iii/public>
Options -Indexes FollowSymLinks
AllowOverride All
Order allow,deny
allow for all
</Directory>
```

### Extra steps for nginx

The nginx configuration file should look something like this:

```
server {
       	listen 80;
       	listen [::]:80;
        # pass the PHP scripts to FastCGI server listening on the php-fpm socket
        location ~ \.php$ {
                try_files $uri =404;
                fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
                fastcgi_read_timeout 600;
        }

       	root /var/www/firefly-iii/public;
       	client_max_body_size 300M;
	    index index.php;

       	server_name firefly.app;

       	location / {
        try_files $uri $uri/ /index.php?$query_string;
        autoindex on;
        sendfile off;
       	}

}
```

## 3. Registering

Surf to your web server. The ``public/`` directory is your root. You may want to change your web server's configuration so you can surf to ``/`` and get Firefly.

You will see a Sign In screen. Use the Register pages to create a new account. After you've created a new account, you will get an introduction screen.

It may seem strange to register on your own website but there you are.


## Installation errors

Some common errors:

### 500 errors, logs are empty

If the logs are empty (``storage/logs``) Firefly can't write to them. See above for the commands. If the logs still remain empty, do you have a the ``vendor`` in your Firefly root? If not, run the Composer commands.

### Unexpected question mark

```
PHP Parse error:  syntax error, unexpected '?' in 
app/Support/Twig/General.php on line 103
```

Firefly III requires PHP 7.0.0.

### BCMath

```
PHP message: PHP Fatal error: Call to undefined function 
FireflyIII\Http\Controllers\bcscale() in
firefly-iii/app/Http/Controllers/HomeController.php on line 76
```

Solution: you haven't enabled or installed the BCMath module.

### intl

Errors such as these:

```
production.ERROR: exception 
'Symfony\Component\Debug\Exception\FatalErrorException' with message
'Call to undefined function FireflyIII\Http\Controllers\numfmt_create()'
in firefly-iii/app/Http/Controllers/Controller.php:55
```

Solution: You haven't enabled or installed the Internationalization extension.

If you are running FreeBSD, install ``pecl-intl``.