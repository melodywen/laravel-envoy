@setup

    /**
    * 1. 默认 初始化web环境的php版本  默认的php 版本为 7.1
    */
    $php_version = $php_version ?? 7.1;
    $php_version = (int)trim($php_version, 'php');

    /**
    * 2. ngnix 的站点配置 可以传递的参数有
    * @param $host             虚拟主机的 host 地址
    * @param $root_dir         虚拟主机的 项目根目录
    * @param $php_fpm          使用的 php-fpm 的版本名称
    * @param $project_name     使用的项目名称
    */
    $root_dir = $root_dir ?? '/vagrant';
    $host = $host ?? 'localhost';
    $php_fpm = $php_fpm ?? $php_version;
    $php_fpm = (int)trim($php_fpm, 'php');
    $project_name = $project_name ?? 'laravel';

    // 2.2 得到相对值
    $realPath = rtrim($root_dir, '/') . '/' . $project_name . '/public';
    $realPathPattern = str_replace('/', '\/', $realPath);

@endsetup