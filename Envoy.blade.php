{{
$php_version=7.1        // php版本
}}

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