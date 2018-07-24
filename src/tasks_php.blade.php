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
    printf "\nexport PATH=~/.composer/vendor/bin:\$PATH" | tee -a ~/.bash_profile

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


@task('task-php-optimize')
    {{--1. 修改 php cli 的内容--}}
    sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/{{ $php_version }}/cli/php.ini
    sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/{{ $php_version }}/cli/php.ini

    {{--2. 修改 php fpm 的内容--}}
    sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/{{ $php_version }}/fpm/php.ini
    sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/{{ $php_version }}/fpm/php.ini
    sed -i "s/upload_max_filesize = .*/upload_max_filesize = 50M/" /etc/php/{{ $php_version }}/fpm/php.ini
    sed -i "s/post_max_size = .*/post_max_size = 50M/" /etc/php/{{ $php_version }}/fpm/php.ini
    sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/{{ $php_version }}/fpm/php.ini

    sed -i "s/pm.max_children =.*/pm.max_children = 300/" /etc/php/{{ $php_version }}/fpm/pool.d/www.conf
    sed -i "s/pm.start_servers =.*/pm.start_servers = 20/" /etc/php/{{ $php_version }}/fpm/pool.d/www.conf
    sed -i "s/pm.min_spare_servers =.*/pm.min_spare_servers = 5/" /etc/php/{{ $php_version }}/fpm/pool.d/www.conf
    sed -i "s/pm.max_spare_servers =.*/pm.max_spare_servers = 35/" /etc/php/{{ $php_version }}/fpm/pool.d/www.conf

    {{--3. 重启 --}}
    /etc/init.d/php{{ $php_version }}-fpm restart

    echo '-----------------------------------------------------------------'
    echo '| php optimize  success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $php_version }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('task-php-display_errors-on')

    {{--1. 修改 php cli 的内容--}}
    sed -i "s/display_errors = .*/display_errors = On/" /etc/php/{{ $php_version }}/cli/php.ini
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_NOTICE \& ~E_STRICT \& ~E_DEPRECATED/" /etc/php/{{ $php_version }}/cli/php.ini

    {{--2. 修改 php fpm 的内容--}}
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_NOTICE \& ~E_STRICT \& ~E_DEPRECATED/" /etc/php/{{ $php_version }}/fpm/php.ini
    sed -i "s/display_errors = .*/display_errors = On/" /etc/php/{{ $php_version }}/fpm/php.ini

    {{--3. 重启 --}}
    /etc/init.d/php{{ $php_version }}-fpm restart

    echo '-----------------------------------------------------------------'
    echo '|  php display errors success to turn on  !!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $php_version }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('task-php-display_errors-off')

    {{--1. 修改 php cli 的内容--}}
    sed -i "s/display_errors = .*/display_errors = Off/" /etc/php/{{ $php_version }}/cli/php.ini
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_DEPRECATED \& ~E_STRICT/" /etc/php/{{ $php_version }}/cli/php.ini

    {{--2. 修改 php fpm 的内容--}}
    sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_DEPRECATED \& ~E_STRICT/" /etc/php/{{ $php_version }}/fpm/php.ini
    sed -i "s/display_errors = .*/display_errors = Off/" /etc/php/{{ $php_version }}/fpm/php.ini

    {{--3. 重启 --}}
    /etc/init.d/php{{ $php_version }}-fpm restart

    echo '-----------------------------------------------------------------'
    echo '| php display errors success to  turn off !!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $php_version }}'
    echo '-----------------------------------------------------------------'
@endtask

@task('task-php-change-version')
    update-alternatives --set php /usr/bin/php{{ $php_version }}

    echo '-----------------------------------------------------------------'
    echo '| php version change is success !!! congratulation you ^_^ ^_^ ^_^ '
    echo '| php-version: {{ $php_version }}'
    echo '-----------------------------------------------------------------'
@endtask