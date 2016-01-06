### Environment Setup
These instructions will get you a working Laravel environment with Nginx and MySQL.  
For development environments instructions for Vagrant are below.

#### Nginx
1. Download the latest Nginx Stable source distribution
2. Ensure that PCRE libraries are installed.  If not:

  ```console
  sudo yum install pcre-devel
  ```
3. Perform installation:

  ```console
  tar -zxvf nginx-1.8.0.tar.gz
  cd nginx-1.8.0
  ./configure --prefix=/usr/local/nginx-1.8.0 && make && make install
  cd /usr/local
  ln -s nginx-1.8.0 nginx
  ```
4. Add the following to the $NGIX_HOME/conf/nginx.conf `location` node:

  ```console
  try_files $uri $uri/ /index.php?$query_string;
  ```

#### PHP
1. Download the latest PHP source distribution
2. Ensure that the following libraries are installed:
  -- OpenSSL 
  -- libxml2
3. Check dependencies on the Laravel site: https://laravel.com/docs/5.2
4. Perform the installation:

  ```console
  tar -jxvf php-7.0.1.tar.bz2
  cd php-7.0.1
  ./configure \
  --prefix=/usr/local/php-7.0.1 \
  --enable-fpm \
  --with-mysql \
  --with-openssl \
  --with-pdo-mysql \
  --enable-mbstring
  make && make test && make install
  ```

### Dev Environment Setup

#### Virtual Box
1. Install as per instructions: https://www.virtualbox.org/wiki/Downloads

#### Vagrant

#### Composer
 1. Install the latest composer: https://getcomposer.org/download/

   ```console
   curl -sS https://getcomposer.org/installer | php
   cp composer.phar /usr/local/bin/composer
   ```

#### Laravel
1. Install Laravel with Composer

  ```console
  composer global require "laravel/installer"
  ```
2. Add `~/.composer/vendor/bin` to your PATH

3. Create a new project

  ```console
  laravel new pease
  ```

  If you are behind a proxy and the above command fails try

  ```console
  composer create-project --prefer-dist laravel/laravel pease
  ```
