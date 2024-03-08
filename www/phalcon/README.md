# 采密王API
## 安装
* 拉取本仓库代码

* 执行 ```composer install```

* 添加nginx配置：
```shell
server {
    listen 80;
    server_name api.caimiwang.com.cn;

    location / {
        if ($request_uri ~ (.+?\.php)(|/.+)$ ) {
            break;
        }

        if (!-e $request_filename) {
            rewrite ^/(.*)$ /index.php?_url=/$1;
        }
    }

    location ~ \.php {
        fastcgi_pass  phalconphp7:9000;
        root /var/www/html/aip.caimiwang.com.cn/public;
        fastcgi_index index.php;
        include fastcgi_params;
        set $real_script_name $fastcgi_script_name;
        if ($fastcgi_script_name ~ "^(.+?\.php)(/.+)$") {
            set $real_script_name $1;
            set $path_info $2;
        }
        fastcgi_param SCRIPT_FILENAME $document_root$real_script_name;
        fastcgi_param SCRIPT_NAME $real_script_name;
        fastcgi_param PATH_INFO $path_info;
    }

    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$ {
            root /usr/share/nginx/html/aip.caimiwang.com.cn/public;
            if (-f $request_filename) {
                expires 1d;
                break;
            }
        }

        location ~ .*\.(js|css)$ {
            root /usr/share/nginx/html/aip.caimiwang.com.cn/public;
            if (-f $request_filename) {
                expires 1d;
                break;
        }
    }

    access_log  /var/log/nginx/api.caimiwang/access.log;
    error_log  /var/log/nginx/api.caimiwang/error.log;
}
```
此nginx配置仅供参考，根据本地环境，自行修改

## 环境配置
* 根目录下的 ```.env``` 文件

## 目录说明
```shell
├── App
│   ├── Cache        //缓存目录
│   │   ├── Data     //数据缓存目录
│   │   │   └── ChinaArea.json  //中国省市区数据
│   │   └── Logs      //项目日志目录
│   ├── Config        //配置目录
│   │   ├── api.php   //第三方接口配置文件 
│   │   ├── routes.php        //API路由配置文件
│   │   └── application.php       //项目配置文件 
│   ├── Controllers            //控制器目录
│   │   ├── BaseController.php       //业务控制器基类，继承自 Core\PhalBaseController.php
│   │   └── V1                  //V1版本目录 
│   │       └── ArticlesController.php
│   ├── Core              //项目核心目录
│   │   ├── PhalBaseController.php      //项目基类控制器
│   │   ├── PhalBaseFilter.php          //项目过滤器类
│   │   ├── PhalBaseLogger.php          //项目日志处理类
│   │   ├── PhalBaseModel.php           //项目模型基类
│   │   ├── PhalBaseTask.php            //项目任务基类
│   │   ├── loader.php                 
│   │   ├── router.php
│   │   └── services.php
│   ├── Libs                           //类库
│   │   ├── Filter.php
│   │   └── Validator.php
│   ├── Models                         //模型目录
│   │   ├── BaseModel.php              //业务模型基类，继承自 Core\PhalBaseModel.php
│   │   └── UsersModel.php
│   └── Repositories                   //业务仓库目录
│       ├── BaseRepository.php         //业务基类
│       └── Users.php
├── Public                             //对外访问目录
│   └── index.php                      //入口文件
└── vendor                             //composer第三方类库
```

## 规范约束
* 程序调用顺序为 ```Controller``` ---> ```Repositories``` ---> ```Models```
* ```Controller``` 负责数据的接收、拦截、过滤与业务仓库的调用、数据的整合并输出数据（不做业务处理）
* ```Repositories``` 负责衔接控制器与模型，并进行业务的处理。单独抽出这层是为了业务复用，避免重复写业务代码
* ```Models``` 负责数据库的基本操作（不做业务处理）。模型类的使用可参考第二代系统


## demo
* 路由写法参照 ```App\Config\routes.php``` 文件
    * http://api.caimiwang.com.cn/v1/articles/list
    * http://api.caimiwang.com.cn/v1/articles/23
* 程序运行示例参照 ```App\Controllers\V1\ArticlesController.php``` 文件