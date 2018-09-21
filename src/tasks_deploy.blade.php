@task('task-deploy-git-pull')
    cd {{ $project_root }}
    git checkout master
    if [ "master" != "{{ $branch_name }}" ]; then
        git checkout {{ $branch_name }}
        git pull origin {{ $branch_name }}
        git submodule foreach git pull
    else
        echo '内测环境禁止在master分支上面操作,此刻不合并代码'
    fi
@endtask

@task('task-deploy-composer')
    cd {{ $project_root }}
    composer install --optimize-autoloader
    echo 'composer install success'
@endtask

@task('task-deploy-writable')
    cd {{ $project_root }}
    chmod -R 775 storage bootstrap
    chown -R www-data storage bootstrap
    echo 'change directory can be writable success'
@endtask

@task('task-deploy-migrate')
    cd {{ $project_root }}
    php artisan migrate
@endtask

@task('task-deploy-seed')
    cd {{ $project_root }}
    php artisan --seed
@endtask

@task('task-deploy-config-cache')
    cd {{ $project_root }}
    php artisan config:cache
@endtask

@task('task-deploy-route-cache')
    cd {{ $project_root }}
    php artisan route:cache
@endtask