@setup
    $envoy_system_url = 'vendor/mr-jiawen/laravel-envoy/';
    $envoy_system_url = rtrim($envoy_system_url, '/');
@endsetup

@import( $envoy_system_url . '/src/default_setup.blade.php')

@import( $envoy_system_url . '/src/linux_tasks.blade.php')

@import( $envoy_system_url .'/src/nginx_tasks.blade.php')

@import( $envoy_system_url .'/src/php_tasks.blade.php')
