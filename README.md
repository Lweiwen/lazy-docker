# php框架测试
docker-compose一键安装常用服务。

## 介绍
集中测试php框架 hyperf,webman,laravel(s),phalcon 性能

## 目录/文件说明

| 目录/文件                    | 描述                                                           |
|:-------------------------|:-------------------------------------------------------------|
| ./www/                   | 代码保存目录, 映射到php和nginx容器的/www目录                                |
| ./data/                  | 默认所有容器的数据均保存在该目录, 通过修改环境DATA_DIR更换数据存储位置, 每个容器的数据保存路径可单独修改   |
| ./logs/                  | 默认所有容器的数据均保存在该目录, 通过修改环境LOG_DIR更换数据存储位置, 每个容器的数据保存路径可单独修改    |
| ./services/              | 服务根目录, Dockfile和服务配置均放在该目录                                   |
| ./docker-compose.dev.yml | php+mysql+nginx+redis等服务                                     |
| ./env.sample             | 环境变量配置                                                       |

## 使用方法

- 拉取代码: `git clone https://github.com/Lweiwen/lazy-docker.git ~/;`
- 复制默认环境变量配置:`cp .env.sample .env`, 自行按需修改。
- `cp docker-compose.dev.yml docker-compose.yml`, 自行注释不需要的服务。
- `docker-compose up -d`启动开发环境。

## 框架使用
注意：所有框架均要进入对应的容器所在文件夹下执行`composer install`命令生成所需依赖。

|框架|php容器| 使用                                                                     |
|:-----:|:-----:|:-----------------------------------------------------------------------|
|hyperf | php82 | `docker exec -it php82 bash` 进入容器后执行 `php bin/hyperf.php start` 启动服务   |
|webman  | php82 | `docker exec -it php82 bash` 进入容器后执行 `php start.php start` 启动服务        |
|laravel | php80 |                                                                        |
|laravels | php80 | `docker exec -it php80 bash` 进入容器后执行 `php bin/laravels.php start` 启动服务 |
|phalcon  | php82 |                                                                        |


## nginx配置测试框架域名
注：其中已内置如下域名配置文件，请在本地host配置域名指向对应域名地址。

| 框架       | 域名               |
|:---------|:-----------------|
| hyperf   | hyperf.ljh.net   |
| laravel  | laravel.ljh.net  |
| laravels | laravels.ljh.net |
| phalcon  | phalcon.ljh.net  |
| webman   | webman.ljh.net   |


## 环境变量说明

见`.env`文件中的注释

## 服务通信说明

- nginx中连接php-fpm:
```
location ~* \.php {
   // 以上省略其他配置
   // fastcgi_pass php72:9000;
   // fastcgi_pass php74:9000;
   // fastcgi_pass php80:9000;
   fastcgi_pass php82:9000;
}

```

- 代码连接mysql:
```
mysql_host='mysql5'
mysql_port=3306
```

- 代码中连接redis:
```
redis_host='redis5'
redis_port=6379
```