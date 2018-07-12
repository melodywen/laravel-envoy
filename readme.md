# laravel 中 envoy 远程部署的示例代码；

## 第一部分 对 envoy.blade.php 的概述
### 1. 全局变量的定义：
```$xslt
$php_version=7.1        // php版本
```
### 2. 命令的介绍
1. web环境的初始化: 会安装 nginx mysql redis php  git composer 等等
    ```$xslt
    envoy run env-init --phpVersion=php7.1
    ```
2. composer换源命令
    ```$xslt
    envoy run composer-china-mirror   切换中国镜像
    envoy run composer-office-mirror  切换官方镜像
    ```
3. 新建nginx站点 (新建一个站点需要传递 fpm值 [php5.6,php7.0,php7.1,php7.2]，项目名称、域名)
    ```$xslt    
    envoy run nginx-site-add  --phpFPM=php7.0 --projectName=laravel --host=www.cjw.com
    ```
    * 此站点配置主要是针对与laravel 的项目站点配置，它会自动配置到项目的public下面
    * 根路径已经是设置好了，默认值是 `/vagrant`,如果是线上，请改为 `/var/www`
    * 同一个主机里，不同的项目可以使用 不同的php版本。指定phpfpm版本即可
    
4. 移除站点
    ```$xslt
    envoy run nginx-site-remove --host=www.cjw.com
    ```
    * 原理是直接删除站点软连接，然后重启nginx
    
5. 安装多个不同版本的php, 可以是 php5.6,php7.0,php7.1,php7.2.
    ```$xslt
    envoy run install-php --phpVersion=php7.0
    ```
6. php 常规优化 , 可以是 php5.6,php7.0,php7.1,php7.2.
    ```$xslt
    envoy run php-optimize --phpVersion=php7.0
    ```
7. php 是否展示错误
   ```$xslt
    envoy run php-display_errors-on --phpVersion=php7.0   展示错误
    envoy run php-display_errors-off --phpVersion=php7.0  关闭错误展示
    ```