

@task('task-php-sources')
    apt-get install -y language-pack-en-base
    locale-gen en_US.UTF-8
    LC_ALL="en_US.UTF-8"
    LC_CTYPE="en_US.UTF-8"
    LANG="en_US.UTF-8"

    apt-get install -y software-properties-common
    add-apt-repository ppa:ondrej/php
    apt-get update

    echo '-----------------------------------------------------------------'
    echo '| task-php-sources command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: change php source success'
    echo '-----------------------------------------------------------------'
@endtask

@task('task-php-install')
    {{--1. 安装指定版本的php--}}
    apt-get -y install php{{$php_version}}
    apt-get -y install php{{$php_version}}-fpm
    echo '11'
    apt-get install -y  mcrypt php{{$php_version}}-mcrypt php{{$php_version}}-mysql \
    php{{$php_version}}-curl php{{$php_version}}-gd \
    php{{$php_version}}-dom php{{$php_version}}-mbstring \
    php{{$php_version}}-pgsql php{{$php_version}}-sqlite3 \
    php{{$php_version}}-apcu php{{$php_version}}-mcrypt \
    php{{$php_version}}-imap php{{$php_version}}-memcached \
    php{{$php_version}}-readline php{{$php_version}}-xdebug \
    php{{$php_version}}-xml php{{$php_version}}-zip \
    php{{$php_version}}-intl php{{$php_version}}-bcmath \
    php{{$php_version}}-soap

    echo '----------------------------------------------------------------------'
    echo '| task-php-install command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: install success of php{{ $php_version }}'
    echo '----------------------------------------------------------------------'
@endtask

@task('task-php-composer-install')
    {{--1. 安装compser--}}
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

    {{--3. Add Co mposer Global Bin To Path--}}
    printf "\nPATH=\"$(composer config -g home 2>/dev/null)/vendor/bin:\$PATH\"\n" | tee -a ~/.profile

    echo '--------------------------------------------------------------------------------------'
    echo '| task-php-composer-install command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: composer install success'
    echo '--------------------------------------------------------------------------------------'
@endtask


{{--composer 切换中国镜像--}}
@task('task-php-composer-china-mirror')
    composer config -g repo.packagist composer https://packagist.phpcomposer.com
    echo '--------------------------------------------------------------------------------------'
    echo '| task-php-composer-china-mirror command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: composer mirror change success'
    echo '--------------------------------------------------------------------------------------'
@endtask

{{--composer 切换官方镜像--}}
@task('task-php-composer-office-mirror')
    composer config -g repo.packagist composer https://packagist.org
    echo '--------------------------------------------------------------------------------------'
    echo '| task-php-composer-office-mirror command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: composer mirror change success'
    echo '--------------------------------------------------------------------------------------'
@endtask
