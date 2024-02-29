# DS
docker-compose一键安装常用服务。


## 目录/文件说明

|目录/文件|描述|
|--:|--:|
|./www/|代码保存目录, 映射到php和nginx容器的/wwww目录|
|./data/|默认所有容器的数据均保存在该目录, 通过修改环境DATA_DIR更换数据存储位置|
|./logs/|默认所有容器的数据均保存在该目录, 通过修改环境LOG_DIR更换数据存储位置|
|./services/|服务根目录, Dockfile和服务配置均放在该目录|
|./docker-compose.yml|docker-compose配置|
|./.dev.bashrc|包含快捷进入容器和执行容器内部命令的bash配置|
|./env.sample|环境变量配置|

## 使用方法

- 拉取代码: `git clone https://github.com/Lweiwen/lazy-docker.git; cd ~/lazy-docker;`
- 复制默认环境变量配置:`cp .env.sample .env`, 自行按需修改。
- 添加快捷命令到.zshrc:  `echo "source ~/lazy-docker/.dev.bashrc" >> ~/.zshrc;` 或者.bashrc:`echo "source ~/lazy-docker/.dev.bashrc" >> ~/.bashrc;`, 或者只复制需要的命令函数到你的终端配置文件中。
- `docker-compose up -d`启动开发环境。
- 访问localhost，网页显示OK则正常。


## .dev.bashrc中的命令列表
|命令|说明|
|:--|--|
|dphp|进入PHPxx容器, xx为版本号, 如dphp72进入php7.2的容器|
|php|执行对应版本的php命令|
|composer|执行对应php版本的composer命令, 如执行php7.2版本的composer命令, 则composer72|
|dmysql|进入mysql容器, 如dmysql5|
|mysql|执行mysl命令|
|dredis|进入redis容器, 如dredis5|
|dnginx|进入nginx容器|
|nginx|执行nginx容器的nginx命令|

## 环境变量说明

见`.env`文件中的注释

## 服务通信说明

- nginx中连接php-fpm:
```
location ~* \.php {
     fastcgi_pass php:9000;
}

```

- 代码连接mysql:
```
mysql_host='mysql'
mysql_port=3306
```

- 代码中连接redis:
```
redis_host='redis'
redis_port=6379
```
