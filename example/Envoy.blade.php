

@servers(['web' => 'root@192.168.33.10'])

{{--初始化安装web 环境 ： nginx 、mysql  、redis 、git 、vim    php--}}
@task('env-init',['on' => 'web'])


     {{--4. 安装 mysql 时候需要输入密码--}}
    sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password password 123456'
    sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password_again password 123456'

    {{--5. 安装web 应用以及基础应用--}}
    apt-get -y install nginx mysql-server mysql-client  redis-server git curl unzip vim

    {{--6. 安装指定版本的php--}}
    apt-get install -y software-properties-common
    LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
    apt-get update
    apt-get -y install php{{$phpVersion}}
    apt-get -y install php{{$phpVersion}}-fpm

    apt-get install -y mcrypt php{{$phpVersion}}-mcrypt php{{$phpVersion}}-mysql \
    php{{$phpVersion}}-curl php{{$phpVersion}}-gd \
    php{{$phpVersion}}-dom php{{$phpVersion}}-mbstring \
    php{{$phpVersion}}-pgsql php{{$phpVersion}}-sqlite3 \
    php{{$phpVersion}}-apcu php{{$phpVersion}}-mcrypt \
    php{{$phpVersion}}-imap php{{$phpVersion}}-memcached \
    php{{$phpVersion}}-readline php{{$phpVersion}}-xdebug \
    php{{$phpVersion}}-xml php{{$phpVersion}}-zip \
    php{{$phpVersion}}-intl php{{$phpVersion}}-bcmath \
    php{{$phpVersion}}-soap

    {{--7. 安装compser--}}
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

    {{--8. Add Co mposer Global Bin To Path--}}
    printf "\nPATH=\"$(composer config -g home 2>/dev/null)/vendor/bin:\$PATH\"\n" | tee -a ~/.profile

    echo '-----------------------------------------------------------------'
    echo '| environment install success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| install service: nginx redis git composer'
    echo '| php-version: {{ $phpVersion }}'
    echo '| mysql-version: {{ 5.7 }}'
    echo '-----------------------------------------------------------------'
@endtask

{{--composer 切换中国镜像--}}
@task('composer-china-mirror',['on' => 'web'])
    composer config -g repo.packagist composer https://packagist.phpcomposer.com
@endtask
{{--composer 切换官方镜像--}}
@task('composer-office-mirror',['on' => 'web'])
    composer config -g repo.packagist composer https://packagist.org
@endtask

@task('nginx-site-add',['on' => 'web'])
    cd /etc/nginx/sites-available
    {{--1. 强行新建一个站点文件--}}
    cp default {{ $host }}
    {{--2. 修改文件中的内容--}}
    sed -i "/^#/d" {{ $host }}
    sed -i "s/listen 80 default_server/listen 80/" {{ $host }}
    sed -i "/listen \[::\]:80 default_server/"d {{ $host }}
    sed -i "s/root \/var.*/root {{ $realPathPattern }};/" {{ $host }}
    sed -i "s/index index.html/index index.php index.html/" {{ $host }}
    sed -i "s/server_name _/server_name {{ $host }}/" {{ $host }}

    sed -i "/try_files \$uri \$uri\/ =404/a\ \t\t try_files \$uri \$uri\/ \/index.php?\$query_string;" {{ $host }}
    sed -i "s/try_files \$uri \$uri\/ =404/#try_files \$uri \$uri\/ =404/" {{ $host }}

    sed -i "/location ~ \\\.php/i\ \n\tcharset utf-8; \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \tlocation = \/favicon.ico \{ access_log off; log_not_found off; \}" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \tlocation = \/robots.txt  \{ access_log off; log_not_found off; \}  \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \terror_page 404 \/index.php;  \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \taccess_log /var/log/nginx/{{ $host }}-access.log;  \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \terror_log /var/log/nginx/{{ $host }}-error.log error;  \n" {{ $host }}


    sed -i "/location ~ \\\.php/a\ \t\tfastcgi_split_path_info ^(.+\.php)(/.+)$;" {{ $host }}
    sed -i "s/#location ~ \\\.php/location ~ \\\.php/" {{ $host }}
    sed -i "s/#\(.*include snippets\/fastcgi-php\.conf\)/\1/" {{ $host }}
    sed -i "s/#\(.*fastcgi_pass unix:\/run\/php\/php\)7\.0\(-fpm\.sock\)/\1{{ $phpFPM }}\2/" {{ $host }}


    sed -i "s/#location ~ \/\\\.ht/location ~ \/\.(?!well-known).*/" {{ $host }}
    sed -i "s/#\(.*deny all;\)/\1/" {{ $host }}

    sed -i "s/#}/}/g" {{ $host }}

    {{--3. 软连接 --}}
    cd /etc/nginx/sites-enabled
    rm -f {{$host}}
    ln -s /etc/nginx/sites-available/{{$host}}

    {{--4. 检查nginx配置，然后重启nginx--}}
    nginx -t
    nginx -s stop
    nginx

    echo '-----------------------------------------------------------------'
    echo '| nginx site create success !!! congratulation you,  ^_^ ^_^ ^_^  '
    echo '| host: {{ $host }}                                               '
    echo '| php-fpm: php-{{ $phpFPM }}                                      '
    echo '| projectName: {{ $projectName }}                                 '
    echo '| realPath: {{ $realPath }}                                       '
    echo '-----------------------------------------------------------------'
@endtask

@task('nginx-site-remove',['on' => 'web'])

    {{--1. 移除软连接 --}}
    cd /etc/nginx/sites-enabled
    rm -f {{$host}}

    {{--4. 检查nginx配置，然后重启nginx--}}
    nginx -t
    nginx -s stop
    nginx

    echo '-----------------------------------------------------------------'
    echo '| nginx site remove success !!! congratulation you,  ^_^ ^_^ ^_^  '
    echo '| host: {{ $host }}                                               '
    echo '-----------------------------------------------------------------'
@endtask

@task('install-php',['on' => 'web'])
    {{--1. 安装指定版本的php--}}
    apt-get install -y software-properties-common
    LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
    apt-get update
    apt-get -y install php{{$phpVersion}}
    apt-get -y install php{{$phpVersion}}-fpm

    apt-get install -y mcrypt php{{$phpVersion}}-mcrypt php{{$phpVersion}}-mysql \
    php{{$phpVersion}}-curl php{{$phpVersion}}-gd \
    php{{$phpVersion}}-dom php{{$phpVersion}}-mbstring \
    php{{$phpVersion}}-pgsql php{{$phpVersion}}-sqlite3 \
    php{{$phpVersion}}-apcu php{{$phpVersion}}-mcrypt \
    php{{$phpVersion}}-imap php{{$phpVersion}}-memcached \
    php{{$phpVersion}}-readline php{{$phpVersion}}-xdebug \
    php{{$phpVersion}}-xml php{{$phpVersion}}-zip \
    php{{$phpVersion}}-intl php{{$phpVersion}}-bcmath \
    php{{$phpVersion}}-soap

    echo '-----------------------------------------------------------------'
    echo '| php install success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $phpVersion }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('php-optimize',['on' => 'web'])
    {{--1. 修改 php cli 的内容--}}
    sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/{{ $phpVersion }}/cli/php.ini
    sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/{{ $phpVersion }}/cli/php.ini

    {{--2. 修改 php fpm 的内容--}}
    sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/{{ $phpVersion }}/fpm/php.ini
    sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/{{ $phpVersion }}/fpm/php.ini
    sed -i "s/upload_max_filesize = .*/upload_max_filesize = 50M/" /etc/php/{{ $phpVersion }}/fpm/php.ini
    sed -i "s/post_max_size = .*/post_max_size = 50M/" /etc/php/{{ $phpVersion }}/fpm/php.ini
    sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/{{ $phpVersion }}/fpm/php.ini

    sed -i "s/pm.max_children =.*/pm.max_children = 300/" /etc/php/{{ $phpVersion }}/fpm/pool.d/www.conf
    sed -i "s/pm.start_servers =.*/pm.start_servers = 20/" /etc/php/{{ $phpVersion }}/fpm/pool.d/www.conf
    sed -i "s/pm.min_spare_servers =.*/pm.min_spare_servers = 5/" /etc/php/{{ $phpVersion }}/fpm/pool.d/www.conf
    sed -i "s/pm.max_spare_servers =.*/pm.max_spare_servers = 35/" /etc/php/{{ $phpVersion }}/fpm/pool.d/www.conf

    {{--3. 重启 --}}
    /etc/init.d/php{{ $phpVersion }}-fpm restart

    echo '-----------------------------------------------------------------'
    echo '| php optimize  success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $phpVersion }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('php-display_errors-on',['on' => 'web'])

    {{--1. 修改 php cli 的内容--}}
    sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.1/cli/php.ini
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_NOTICE \& ~E_STRICT \& ~E_DEPRECATED/" /etc/php/7.1/cli/php.ini

    {{--2. 修改 php fpm 的内容--}}
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_NOTICE \& ~E_STRICT \& ~E_DEPRECATED/" /etc/php/7.1/fpm/php.ini
    sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.1/fpm/php.ini

    {{--3. 重启 --}}
    /etc/init.d/php{{ $phpVersion }}-fpm restart

    echo '-----------------------------------------------------------------'
    echo '|  php display errors success to turn on  !!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $phpVersion }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('php-display_errors-off',['on' => 'web'])

    {{--1. 修改 php cli 的内容--}}
    sed -i "s/display_errors = .*/display_errors = Off/" /etc/php/7.1/cli/php.ini
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_DEPRECATED \& ~E_STRICT/" /etc/php/7.1/cli/php.ini

    {{--2. 修改 php fpm 的内容--}}
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_DEPRECATED \& ~E_STRICT/" /etc/php/7.1/fpm/php.ini
    sed -i "s/display_errors = .*/display_errors = Off/" /etc/php/7.1/fpm/php.ini

    {{--3. 重启 --}}
    /etc/init.d/php{{ $phpVersion }}-fpm restart

    echo '-----------------------------------------------------------------'
    echo '| php display errors success to  turn off !!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $phpVersion }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('change-php-version',['on' => 'web'])
    update-alternatives --set php /usr/bin/php{{ $phpVersion }}

    echo '-----------------------------------------------------------------'
    echo '| php version change is success !!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $phpVersion }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('project-clone',['on' => 'web'])
    cd {{ $rootDir }}
    git clone {{ $projectUrl }}
    cd {{ trim( explode('.',strrchr($projectUrl,'/'))[0],'/') }}
    pwd
    chmod -R 777 storage
    composer install

    echo '-----------------------------------------------------------------'
    echo '| git clone project success !!! congratulation you ^_^ ^_^ ^_^ '
    echo '| project-url: {{ $projectUrl }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('project-pull',['on' => 'web'])
    ls /vagrant
    project-pull1
@endtask

@task('project-pull1',['on' => 'web'])
    ls /vagrant
@endtask