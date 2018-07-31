{{--1. linux 的基础操作 --}}
@task('task-linux-general')
    {{--1. 移除 apache2--}}
    apt-get purge apache2 -y

    {{-- 2. Force Locale--}}
    apt-get install -y language-pack-en-base
    locale-gen en_US.UTF-8
    LC_ALL="en_US.UTF-8"
    LC_CTYPE="en_US.UTF-8"
    LANG="en_US.UTF-8"

    {{--3. Set My Timezone--}}
    ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

    echo '----------------------------------------------------------------------------------'
    echo '| task-linux-general command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: uninstall apache、set local language、 set localtime to Asia/Shanghai'
    echo '----------------------------------------------------------------------------------'
@endtask

{{--2. ubuntu 换源 阿里源--}}
@task('task-linux-apt-sources-aliyun')
    {{--1. 移除锁文件--}}
    rm -rf /var/lib/apt/lists/lock
    {{--2. 换源--}}
    echo 'deb http://mirrors.aliyun.com/ubuntu/ xenial main restricted universe multiverse' > /etc/apt/sources.list
    echo 'deb http://mirrors.aliyun.com/ubuntu/ xenial-security main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb http://mirrors.aliyun.com/ubuntu/ xenial-updates main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb http://mirrors.aliyun.com/ubuntu/ xenial-proposed main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb http://mirrors.aliyun.com/ubuntu/ xenial-backports main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb-src http://mirrors.aliyun.com/ubuntu/ xenial main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb-src http://mirrors.aliyun.com/ubuntu/ xenial-security main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb-src http://mirrors.aliyun.com/ubuntu/ xenial-updates main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb-src http://mirrors.aliyun.com/ubuntu/ xenial-proposed main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb-src http://mirrors.aliyun.com/ubuntu/ xenial-backports main restricted universe multiverse' >> /etc/apt/sources.list
    {{--3. 更新--}}
    apt-get update

    echo '------------------------------------------------------------------------------------'
    echo '| task-linux-apt-sources-aliyun command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: it is success to change apt-get sources of aliyun'
    echo '------------------------------------------------------------------------------------'
@endtask

{{--3. ubuntu 换源 官方源--}}
@task('task-linux-apt-sources-office')
    {{--1. 移除锁文件--}}
    rm -rf /var/lib/apt/lists/lock
    {{--2. 换源--}}
    echo 'deb http://us.archive.ubuntu.com/ubuntu/ xenial main restricted' > /etc/apt/sources.list
    echo 'deb http://us.archive.ubuntu.com/ubuntu/ xenial-updates main restricted' >> /etc/apt/sources.list
    echo 'deb http://us.archive.ubuntu.com/ubuntu/ xenial universe' >> /etc/apt/sources.list
    echo 'deb http://us.archive.ubuntu.com/ubuntu/ xenial-updates universe' >> /etc/apt/sources.list
    echo 'deb http://us.archive.ubuntu.com/ubuntu/ xenial multiverse' >> /etc/apt/sources.list
    echo 'deb http://us.archive.ubuntu.com/ubuntu/ xenial-updates multiverse' >> /etc/apt/sources.list
    echo 'deb http://us.archive.ubuntu.com/ubuntu/ xenial-backports main restricted universe multiverse' >> /etc/apt/sources.list
    echo 'deb http://security.ubuntu.com/ubuntu xenial-security main restricted' >> /etc/apt/sources.list
    echo 'deb http://security.ubuntu.com/ubuntu xenial-security universe' >> /etc/apt/sources.list
    echo 'deb http://security.ubuntu.com/ubuntu xenial-security multiverse' >> /etc/apt/sources.list
    {{--3. 更新--}}
    apt-get update

    echo '------------------------------------------------------------------------------------'
    echo '| task-linux-apt-sources-office command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: it is success to change apt-get sources of office'
    echo '------------------------------------------------------------------------------------'
@endtask

{{--4. linux系统安装 常规的应用--}}
@task('task-linux-install-general-application')
    apt-get -y install nginx git curl unzip vim

    echo '----------------------------------------------------------------------------------------------'
    echo '| task-linux-install-general-application command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: install success of nginx git curl unzip vim'
    echo '----------------------------------------------------------------------------------------------'
@endtask

{{-- 5. linux系统安装 mysql --}}
@task('task-linux-install-mysql5.7')
    {{--4. 安装 mysql 时候需要输入密码--}}
    sudo debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password password 123456'
    sudo debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password_again password 123456'

    apt-get -y install  mysql-server mysql-client

    echo '----------------------------------------------------------------------------------------------'
    echo '| task-linux-install-mysql5.7 command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: install success of mysql5.7'
    echo '----------------------------------------------------------------------------------------------'
@endtask

{{-- 6. linux系统安装 redis --}}
@task('task-linux-install-redis')
    apt-get -y install redis-server

    echo '----------------------------------------------------------------------------------------------'
    echo '| task-linux-install-redis command success!!! congratulation you ^_^ ^_^ ^_^ '
    echo '| describe: install success of redis-server'
    echo '----------------------------------------------------------------------------------------------'
@endtask

@task('task-command')
    {{ $command }}
@endtask