### Environment Setup

#### Nginx
1. Download the latest Nginx Stable source distribution
2. Ensure that PCRE libraries are installed.  If not:

  ```shell
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
