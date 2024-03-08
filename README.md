# DS
docker-compose一键安装常用服务。


## 目录/文件说明

|目录/文件| 描述                                                         |
|--|------------------------------------------------------------|
|./www/| 代码保存目录, 映射到php和nginx容器的/www目录                              |
|./www/go/| go代码保存目录, 映射到go容器的/go目录                                    |
|./data/| 默认所有容器的数据均保存在该目录, 通过修改环境DATA_DIR更换数据存储位置, 每个容器的数据保存路径可单独修改 |
|./logs/| 默认所有容器的数据均保存在该目录, 通过修改环境LOG_DIR更换数据存储位置, 每个容器的数据保存路径可单独修改  |
|./services/| 服务根目录, Dockfile和服务配置均放在该目录                                 |
|./docker-compose.dev.yml| php+mysql+nginx+redis等服务                                   |
|./.dev.bashrc| 包含快捷进入容器和执行容器内部命令的bash配置                                   |
|./env.sample| 环境变量配置                                                     |

## 使用方法

- 拉取代码: `git clone https://github.com/Lweiwen/lazy-docker.git ~/;`
- 复制默认环境变量配置:`cp .env.sample .env`, 自行按需修改。
- `cp docker-compose.dev.yml docker-compose.yml`, 自行注释不需要的服务。
- `docker-compose up -d`启动开发环境。


## .dev.bashrc中的命令列表
|命令|说明|
|:--|--|
|dphpxx|进入PHPxx容器, xx为版本号, 如dphp72进入php7.2的容器|
|phpxx|执行对应版本的php命令, 如执行php7.2版本的php命令, 则php72|
|composerxx|执行对应php版本的composer命令, 如执行php7.2版本的composer命令, 则composer72|
|dmysqlx|进入mysql容器, 如dmysql5|
|dredisx|进入redis容器, 如dredis5|
|dnginx|进入nginx容器|
|nginx|执行nginx容器的nginx命令|

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