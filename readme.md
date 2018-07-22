# laravel 中 envoy 远程部署的示例代码；
## 第一部分 对 envoy.blade.php 的概述
笔者把常用的命令封装成包（这个组件）
### 1.1 在入口文件中仅仅只需要写以下代码即可运用此组件
```
@servers(['web' => 'root@192.168.33.11'])
@import('vendor/mr-jiawen/laravel-envoy/bootstrap.blade.php')
```

### 1. 其中bootstrap.blade.php 为引导文件：
> 包括全局变量和子模板

```$xslt
# 系统变量，禁止更改， 除非把此包进行本地化操作
$envoy_system_url = 'vendor/mr-jiawen/laravel-envoy/';
 
# php版本 (默认)
$php_version=7.1        

# 虚拟主机的 host 地址 (默认)
$host = 'localhost'            

# 虚拟主机的 项目根目录 (默认)
$root_dir = '/vagrant'         

# 使用的 php-fpm 的版本名称 (默认)
$php_fpm = 7.1        

# 使用的项目名称 (默认)
$project_name = 'laravel'   
```
### 2. 环境远程部署
1. web环境的初始化: 会安装 nginx mysql redis php  git composer 等等
    ```$xslt
    envoy run story-linux
    envoy run story-php-install --php_version=7.1
    ```
3. 新建nginx站点 (新建一个站点需要传递 fpm值 [php5.6,php7.0,php7.1,php7.2]，项目名称、域名)
    ```$xslt    
    envoy run task-nginx-site-add  --php_fpm=php7.0 --project_name=laravel --host=www.cjw.com
    ```
    * 此站点配置主要是针对与laravel 的项目站点配置，它会自动配置到项目的public下面
    * 根路径已经是设置好了，默认值是 `/vagrant`,如果是线上，请改为 `/var/www`
    * 同一个主机里，不同的项目可以使用 不同的php版本。指定php-fpm版本即可
    
4. 移除站点
    ```$xslt
    envoy run task-nginx-site-remove --host=www.cjw.com
    ```
    * 原理是直接删除站点软连接，然后重启nginx
    
5. 安装多个不同版本的php, 可以是 php5.6,php7.0,php7.1.
    ```$xslt
    envoy run task-php-install --php_version=php7.0
    ```
6. php 常规优化 , 可以是 php5.6,php7.0,php7.1
    ```$xslt
    envoy run task-php-optimize --php_version=php7.0
    ```
7. php 是否展示错误
   ```$xslt
    envoy run task-php-display_errors-on --php_version=php7.0   展示错误
    envoy run task-php-display_errors-off --php_version=php7.0  关闭错误展示
    ```    
8. php cli 的版本切换
    ```$xslt
    envoy run task-php-change-version --php_version=php7.0  
    ```
    
### 3. 项目远程部署
1. 简单的克隆项目 (待续)
   
2. 其他的请根据实际进行自定义 envoy 命令
    * 包括git 拉去代码
    * 包括 持续部署