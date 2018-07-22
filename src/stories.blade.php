
{{--linux 环境安装 ： 包括 nginx  mysql   redis  git --}}
@story('story-linux',['on' => 'web'])
    task-linux-apt-sources-aliyun
    task-linux-general
    task-linux-install-general-application
    task-linux-install-mysql5.7
    task-linux-install-redis
@endstory

{{--php 安装、包括 composer 安装 、 php 优化--}}
@story('story-php-install',['on' => 'web'])
    task-php-sources
    task-php-install
    task-php-composer-install
    task-php-composer-china-mirror
    task-php-optimize
    task-php-display_errors-on
@endstory