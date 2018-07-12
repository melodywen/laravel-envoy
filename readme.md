# laravel 中 envoy 远程部署的示例代码；

## 第一部分 对 envoy.blade.php 的概述
### 1. 全局变量的定义：
```$xslt
$php_version=7.1        // php版本
```
### 2. 命令的介绍
1. web环境的初始化: 会安装 nginx mysql redis php  git composer 等等
    ```$xslt
    envoy run env-init 
    ```
2. composer换源命令
    ```$xslt
    envoy run composer-china-mirror   切换中国镜像
    envoy run composer-office-mirror  切换官方镜像
    ```