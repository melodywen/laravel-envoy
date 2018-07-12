@setup
    // 默认 初始化web环境的php版本
    $php_version=7.1;

    // ngnix 的站点配置 可以传递的参数有  $host 、$phpFPM 、$projectName
    $rootDir = '/vagrant';

    $host = $host ?? 'localhost';
    $phpFPM = $phpFPM ?? $php_version;
    $phpFPM = trim($phpFPM,'php');
    $projectName = $projectName ?? 'laravel';

    $realPath = rtrim($rootDir,'/').'/'. $projectName . '/public';
    $realPathPattern = str_replace('/','\/',$realPath);

@endsetup

@servers(['web' => 'root@192.168.33.10'])

{{--初始化安装web 环境 ： nginx 、mysql  、redis 、git 、vim    php--}}
@task('env-init',['on' => 'web'])
    apt-get update

    {{--1. 移除 apache2--}}
    apt-get purge apache2 -y

    {{-- 2. Force Locale--}}
    apt-get install -y language-pack-en-base
    locale-gen en_US.UTF-8

    {{--3. Set My Timezone--}}
    ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

     {{--4. 安装 mysql 时候需要输入密码--}}
    sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password password 123456'
    sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password_again password 123456'

    {{--5. 安装web 应用以及基础应用--}}
    apt-get -y install nginx mysql-server mysql-client  redis-server git curl unzip vim

    {{--6. 安装指定版本的php--}}
    apt-get install -y software-properties-common
    LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
    apt-get update
    apt-get -y install php{{$php_version}}
    apt-get -y install php{{$php_version}}-fpm

    apt-get install -y mcrypt php{{$php_version}}-mcrypt php{{$php_version}}-mysql \
    php{{$php_version}}-curl php{{$php_version}}-gd \
    php{{$php_version}}-dom php{{$php_version}}-mbstring \
    php{{$php_version}}-pgsql php{{$php_version}}-sqlite3 \
    php{{$php_version}}-apcu php{{$php_version}}-mcrypt \
    php{{$php_version}}-imap php{{$php_version}}-memcached \
    php{{$php_version}}-readline php{{$php_version}}-xdebug \
    php{{$php_version}}-xml php{{$php_version}}-zip \
    php{{$php_version}}-intl php{{$php_version}}-bcmath \
    php{{$php_version}}-soap

    {{--7. 安装compser--}}
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

    {{--8. Add Co mposer Global Bin To Path--}}
    printf "\nPATH=\"$(composer config -g home 2>/dev/null)/vendor/bin:\$PATH\"\n" | tee -a ~/.profile

    echo '-----------------------------------------------------------------'
    echo '| environment install success!!! congratulation you ^_^ ^_^ ^_^ | '
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

    cd /etc/nginx/sites-enabled
    rm -f {{$host}}
    ln -s /etc/nginx/sites-available/{{$host}}

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