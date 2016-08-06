---
layout: page
title: Installation guide
permalink: /installation-guide/
---

# Normal installation

Have you seen the [system requirements](../system-requirements/)? If so, please continue!


## 1. Preparing your (Ubuntu) server

This is what I do when I install Firefly III:

```
sudo apt-get update; sudo apt-get upgrade
sudo apt-get install -y mysql-server
sudo mysql_install_db; sudo mysql_secure_installation

sudo apt-get install -y language-pack-en-base
sudo LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

sudo apt-get update;sudo apt-get upgrade

sudo apt-get install -y php7.0 php7.0-cli php7.0-common php7.0-fpm php7.0-mysql php7.0-curl php7.0-gd php7.0-imap php7.0-intl php7.0-json php7.0-mcrypt php7.0-readline php7.0-tidy php7.0-zip php7.0-bcmath php7.0-xml php7.0-mbstring php7.0-sqlite php7.0-bz2

cd ~; wget https://getcomposer.org/download/1.0.2/composer.phar; chmod +x composer.phar; sudo mv composer.phar /usr/local/bin/composer; sudo composer selfupdate

```

That should install MySQL and the correct version of PHP. If you want to use Apache or nginx is up to you.


## 2. Preparing your CentOS server

You can skip this step if you do not run CentOS.

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

This should get you up and running. Now, follow the "installation steps" at the top of this page.

## 3. Installation steps

SSH into your server and go to the directory where you want to install Firefly III. Please keep in mind that the web root of Firefly III is in the ``firefly-iii/public/`` directory, so you may need to update your web server configuration.

Once you're there, run the following command:

* ``git clone https://github.com/JC5/firefly-iii.git --depth 1``

Or variants:

* ``git clone https://github.com/JC5/firefly-iii.git some-other-dir --depth 1``
* ``git clone https://github.com/JC5/firefly-iii.git --depth 1`` (defaults to ``firefly-iii``)

***

 Then, run this command:

``cp firefly-iii/.env.example firefly-iii/.env``

Open ``firefly-iii/.env``.

* Change the ``DB_*`` settings as you see fit.
* Update the ``MAIL_*`` settings as you see fit.
* If you want to track statistics, update the Google Analytics ID.
* Set ``RUNCLEANUP`` to ``false``
* Set ``SITE_OWNER`` to your own email address.

Once you've set this up, run the following commands:

* ``cd firefly-iii`` (or how you've named your folder)
* ``composer install --no-dev``
* ``php artisan migrate --seed --env=production``

Finally, make sure that the storage directories are writeable, _for example_ by using these commands:

* ``chown -R www-data:www-data storage``
* ``chmod -R g+w storage``

### Registering

Surf to your web server, the ``public/`` directory is your root. You may want to change your web server's configuration so you can surf to ``/`` and get Firefly.

You will see a Sign In screen. Use the Register pages to create a new account. After you've created a new account, you will get an introduction screen.

### Registering

Surf to your web server, the ``public/`` directory is your root. You may want to change your web server's configuration so you can surf to ``/`` and get Firefly.

You will see a Sign In screen. Use the Register pages to create a new account. After you've created a new account, you will get an introduction screen.


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