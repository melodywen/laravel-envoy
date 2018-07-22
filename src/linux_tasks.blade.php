{{--1. linux 的基础操作 --}}
@task('task-linux-general')
    {{--1. 移除 apache2--}}
    apt-get purge apache2 -y

    {{-- 2. Force Locale--}}
    apt-get install -y language-pack-en-base
    locale-gen en_US.UTF-8

    {{--3. Set My Timezone--}}
    ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

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
@endtask

{{--2. ubuntu 换源 官方源--}}
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
@endtask