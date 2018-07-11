{{
$php_version=7.1        // php版本
}}

@servers(['web' => 'vagrant@192.168.33.10'])

{{--初始化安装web 环境 ： nginx 、mysql  、redis 、git 、vim    php--}}
@task('env-init',['on' => 'web'])
    sudo su
    apt-get update

     {{--1. 安装 mysql 时候需要输入密码--}}
    sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password password 123456'
    sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password_again password 123456'

    {{--2. 安装常见的web命令--}}
    apt-get -y install nginx mysql-server mysql-client  redis-server git curl unzip vim

    {{--3. 安装指定版本的php--}}
    apt-get install -y language-pack-en-base
    locale-gen en_US.UTF-8
    apt-get install -y software-properties-common
    LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
    apt-get update
    apt-get -y install php{{$php_version}}
    apt-get -y install php{{$php_version}}-fpm

    apt-get install -y mcrypt php{{$php_version}}-mcrypt php{{$php_version}}-mysql \
    php{{$php_version}}-curl php{{$php_version}}-gd \
    php{{$php_version}}-dom php{{$php_version}}-mbstring

    echo '-----------------------------------------------------------------'
    echo '| environment install success!!! congratulation you ^_^ ^_^ ^_^ | '
    echo '-----------------------------------------------------------------'
@endtask

