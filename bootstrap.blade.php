@setup
    /**
    * 定义envoy 不要覆盖它系统变量
    */
    $envoy_system_url = 'vendor/mr-jiawen/laravel-envoy/';
    $envoy_system_url = rtrim($envoy_system_url, '/');
@endsetup

@setup
    /**
    * 1. 默认 初始化web环境的php版本  默认的php 版本为 7.1
    */
    $php_version = $php_version ?? 7.1;
    $php_version = trim($php_version, 'php');

    /**
    * 2. ngnix 的站点配置 可以传递的参数有
    * @param $host             虚拟主机的 host 地址
    * @param $root_dir         虚拟主机的 项目根目录
    * @param $php_fpm          使用的 php-fpm 的版本名称
    * @param $project_name     使用的项目名称
    * @param $php_type         使用php的类型 一种为 sock 、 一种为FastCGI
    */
    $root_dir = $root_dir ?? '/vagrant';
    $host = $host ?? 'localhost';
    $php_fpm = $php_fpm ?? $php_version;
    $php_fpm = trim($php_fpm, 'php');
    $project_name = $project_name ?? 'laravel';
    $php_type = $php_type == 'FastCGI' ? 'FastCGI' : 'Sock';

    // 2.2 得到相对值
    $real_path = rtrim($root_dir, '/') . '/' . $project_name . '/public';
    $real_path_pattern = str_replace('/', '\/', $real_path);

    /**
    * 通用命令
    * @param $command          执行远程服务器命令
    */
    $command = $command ?? 'pwd';
@endsetup


{{--导入--}}
@import( $envoy_system_url . '/src/tasks_linux.blade.php')

@import( $envoy_system_url .'/src/tasks_nginx.blade.php')

@import( $envoy_system_url .'/src/tasks_php.blade.php')

@import( $envoy_system_url .'/src/stories.blade.php')
