
@task('task-nginx-site-add')
    cd /etc/nginx/sites-available
    {{--1. 强行新建一个站点文件--}}
    rm -f {{ $host }}
    cp default {{ $host }}
    {{--2. 修改文件中的内容--}}
    sed -i "/^#/d" {{ $host }}
    sed -i "s/listen 80 default_server/listen 80/" {{ $host }}
    sed -i "/listen \[::\]:80 default_server/"d {{ $host }}
    sed -i "s/root \/var.*/root {{ $real_path_pattern }};/" {{ $host }}
    sed -i "s/index index.html/index index.php index.html/" {{ $host }}
    sed -i "s/server_name _/server_name {{ $host }}/" {{ $host }}

    sed -i "/try_files \$uri \$uri\/ =404/a\ \t\t try_files \$uri \$uri\/ \/index.php?\$query_string;" {{ $host }}
    sed -i "s/try_files \$uri \$uri\/ =404/#try_files \$uri \$uri\/ =404/" {{ $host }}

    sed -i "/location ~ \\\.php/i\ \n\tcharset utf-8; \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \tlocation = \/favicon.ico \{ access_log off; log_not_found off; \}" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \tlocation = \/robots.txt  \{ access_log off; log_not_found off; \}  \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \terror_page 404 \/index.php;  \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \taccess_log /var/log/nginx/{{ $host }}-access.log;  \n" {{ $host }}
    sed -i "/location ~ \\\.php/i\ \terror_log /var/log/nginx/{{ $host }}-error.log error;  \n" {{ $host }}


    sed -i "/location ~ \\\.php/a\ \t\tfastcgi_split_path_info ^(.+\.php)(/.+)$;" {{ $host }}
    sed -i "s/#location ~ \\\.php/location ~ \\\.php/" {{ $host }}
    sed -i "s/#\(.*include snippets\/fastcgi-php\.conf\)/\1/" {{ $host }}
    @if($php_type == 'Sock')
        sed -i "s/#\(.*fastcgi_pass unix:\/run\/php\/php\)7\.0\(-fpm\.sock\)/\1{{ $php_fpm }}\2/" {{ $host }}
    @else
        sed -i "s/#\(.*fastcgi_pass.*127\.0\.0\.1:9000\)/\1/" {{ $host }}
    @endif

    sed -i "s/#location ~ \/\\\.ht/location ~ \/\.(?!well-known).*/" {{ $host }}
    sed -i "s/#\(.*deny all;\)/\1/" {{ $host }}

    sed -i "s/#}/}/g" {{ $host }}

    {{--3. 软连接 --}}
    cd /etc/nginx/sites-enabled
    rm -f {{$host}}
    ln -s /etc/nginx/sites-available/{{$host}}

    {{--4. 检查nginx配置，然后重启nginx--}}
    nginx -t
    nginx -s stop
    nginx

    echo '-----------------------------------------------------------------'
    echo '| nginx site create success !!! congratulation you,  ^_^ ^_^ ^_^  '
    echo '| host: {{ $host }}                                               '
    echo '| php-fpm: php-{{ $php_fpm }}                                      '
    echo '| projectName: {{ $project_name}}                                 '
    echo '| realPath: {{ $real_path }}                                       '
    echo '-----------------------------------------------------------------'
@endtask


@task('task-nginx-site-remove')
    {{--1. 移除软连接 --}}
    cd /etc/nginx/sites-enabled
    rm -f {{$host}}

    {{--4. 检查nginx配置，然后重启nginx--}}
    nginx -t
    nginx -s stop
    nginx

    echo '-----------------------------------------------------------------'
    echo '| nginx site remove success !!! congratulation you,  ^_^ ^_^ ^_^  '
    echo '| host: {{ $host }}                                               '
    echo '-----------------------------------------------------------------'
@endtask