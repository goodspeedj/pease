### Environment Setup

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

### PHP
1. Download the latest PHP source distribution
2. Ensure that the following libraries are installed:
  -- OpenSSL 
  -- libxml2
3. Check dependencies on the Laravel site: https://laravel.com/docs/5.2
4. Perform the installation:

  ```console
  tar -jxvf php-7.0.1.tar.bz2
  cd php-7.0.1
  ./configure --prefix=/usr/local/php-7.0.1 --enable-fpm --with-mysql --with-openssl --with-pdo-mysql --enable-mbstring
  ```
