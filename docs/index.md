# hack-igniter

A example project extends of CodeIgniter v3.x

[https://azhai.gitbook.io/hack-igniter/](https://azhai.gitbook.io/hack-igniter/)


## 安装

要求php v5.4及以上版本。

下载最新版本代码，解压并进入目录。

运行下面的命令（需要安装有 composer ），安装依赖包和开发依赖包。

```bash
composer install
```

## 配置

开发环境的配置依然在 application/config/development 下

第一次务必修改 constants.php 中的网址和 database.php 中的数据库连接

## 生成Model

设置好数据库连接后，回到安装目录（即 application 再往上一层目录），执行

```bash
php index.php gen model     #生成Model文件
php index.php gen fake      #写入演示数据
```

自动生成所有表的 Model 文件，放入数据库连接为名的子目录中，并且关联对应 Mixin 。

## 控制器流程

按照 CodeIgniter 规范，访问网址会被对应到“模块/控制器/方法”上，但如果控制中含有

_remap() 方法，则直接执行它。

我们在基础控制器 application/core/MY_Controller.php 中，放置一个 _remap() ，

它的流程是这样：

* 每个控制有个 context 的属性，类型为 array 。
* 如果对应的方法是私有或者保护方法，直接返回 404 错误，当前请求结束。
* 首先执行 initialize() 方法，返回结果作为 context 一部分。同时在这个方法中获取与URL相关的属性，或检查当前用户的权限。
* 接着执行网址中对应的公开方法，查询数据库等操作在这里实现，其结果也是 context 的一部分。
* 最后执行 finalize(）输出，会先找到对应模板文件，再用 context 中数据渲染模板。

## 进一步阅读

查看其他

* [快速入门](./docs/quick_start.md)
