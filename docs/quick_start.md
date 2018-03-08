# hack-igniter

A example project extends of CodeIgniter v3.x


## 安装

要求php v5.4及以上版本。

下载最新版本代码，解压并进入目录。

运行下面的命令（需要安装有 composer ），安装依赖包和开发依赖包。

```bash
composer install
```

## 目录结构

对 CodeIgniter 的目录稍作改动。

在 application/controllers 分成多个子目录，每个子目录是一个模块。

子目录下是控制器和它们的模板，所有控制器名称、文件（不含扩展名）都以 _page 结尾。

application 不再有 views 目录，只有 themes 目录，存放模板的公共布局文件。


```
|--- applictaion/       #代码文件夹
|   |--- cache/             #缓存文件
|   |--- config/            #项目相关的配置
|   |   |--- autoload.php
|   |   |--- constants.php
|   |   |--- development/
|   |   |   |--- config.php
|   |   |   |--- constants.php
|   |   |   |--- database.php
|   |   |   |--- redis.php
|   |   |   |--- routes.php
|   |   |--- hooks.php
|   |   |--- migration.php
|   |   |--- mimes.php
|   |   |--- profiler.php
|   |--- controllers/       #主目录
|   |   |--- home/          #模块
|   |       |--- views/             #视图目录
|   |       |   |--- index/
|   |       |       |--- index.php
|   |       |--- Index_page.php     #控制器
|   |--- core/              #核心程序
|   |--- helpers/           #辅助函数
|   |--- language/          #语言包
|   |--- libraries/         #第三方类库
|   |--- logs/              #日志
|   |--- models/            #模型目录
|   |   |--- default/       #按数据库连接名分开，自动生成Model文件
|   |   |--- fakes/         #为Model创建演示数据
|   |   |   |--- User_fake.php
|   |   |--- mixins/        #附加到Model的功能，比如外键、缓存
|   |       |--- User_mixin.php
|   |--- themes/                #主题目录
|       |--- default/
|           |--- errors/
|           |   |--- error_404.php  #404模板
|           |--- layout.php         #模板布局
|--- static/         #静态文件
|--- composer.json   #依赖管理的配置文件
|--- ctl.sh          #守护进程控制脚本
|--- index.php       #入口文件
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

## 关联查询

在 application/controllers 中加入 demo 子目录来测试

创建 demo/Index_page.php 文件

```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 演示模块的首页控制器
 */
class Index_page extends MY_Controller
{
    public function index()
    {
        //以 User 和 Role 为例
        $this->load->model('default/user_model');
        $this->load->model('default/role_model');
        $this->user_model->with_foreign('role');

        $this->user_model->where('role_id >', 0);
        //自动为每个 user 带上 role 成员
        $users = $this->user_model->all(5);
        if (count($users) > 0) {
            var_export($users[0]);
            var_export($users[0]['role']);
        }
        exit(); //结束，否则会去渲染对应模板
    }
}
```

当然事先需要设置好 application/models/mixins/User_Mixin.php 文件，

设置好上面文件后，要删除 User_model.php 文件，然后用 gen model 命令重新生成。


```php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 系统用户
 */
trait User_mixin
{
    use \Mylib\Behavior\MY_Foreign;

    public function get_relations()
    {
        return [
            'role' => [
                //'type' => FOREIGN_BELONGS_TO, //可省略
                'model' => 'default/role_model',
            ],
        ];
    }
}
```

对于上面的查询来说 Role_Mixin.php 不是必须的，不过反向的关联需要。


```php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 系统角色
 */
trait Role_mixin
{
    use \Mylib\Behavior\MY_Foreign;

    public function get_relations()
    {
        return [
            'users' => [ //一对多关联
                'type' => FOREIGN_HAS_MANY,
                'model' => 'default/user_model',
                'rev_name' => 'role',
                'fkey' => 'role_id',
            ],
            'privileges' => [ //多对多关联
                'type' => FOREIGN_MANY_TO_MANY,
                'model' => 'default/privilege_model',
                'rev_name' => 'roles',
                'fkey' => 'role_id',
                'another_fkey' => 'privilege_id',
                'middle_model' => 'default/role_privilege_model',
            ],
        ];
    }
}
```

特殊的自关联，即有些行是另一些行的 parent ，用特殊常量代替自身的 Model 名。


```php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 后台菜单
 */
trait Menu_mixin
{
    use \Mylib\Behavior\MY_Foreign;

    public function get_relations()
    {
        return [
            'parent' => [
                'model' => FOREIGN_SELF_MODEL,
            ],
            'children' => [
                'type' => FOREIGN_HAS_MANY,
                'model' => FOREIGN_SELF_MODEL,
                'rev_name' => 'parent',
                'fkey' => 'parent_id',
            ],
        ];
    }
}
```

## 数据缓存

使用 Model 到 redis 的缓存，通常是 hash 数据结构。


```php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 系统用户
 */
trait User_mixin
{
    use \Mylib\Behavior\MY_Cacheable;
    use \Mylib\Behavior\MY_Foreign;

    public function __construct()
    {
        parent::__construct();
        $this->add_cache('redis', 'admin');
    }

    public function get_relations()
    {
        return [
            'role' => [
                //'type' => FOREIGN_BELONGS_TO, //可省略
                'model' => 'default/role_model',
            ],
        ];
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        return 'user:' . $condition['username'];
    }
}
```

## 树状结构，多级分类

## 按月分表

## 模板
