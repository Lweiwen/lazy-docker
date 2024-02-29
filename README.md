# DS
docker-compose一键安装常用服务。


## 目录/文件说明

|目录/文件|描述|
|--:|--:|
|./www/|代码保存目录, 映射到php和nginx容器的/wwww目录|
|./www/go/|go代码保存目录, 映射到go容器的/go目录|
|./data/|默认所有容器的数据均保存在该目录, 通过修改环境DATA_DIR更换数据存储位置, 每个容器的数据保存路径可单独修改|
|./logs/|默认所有容器的数据均保存在该目录, 通过修改环境LOG_DIR更换数据存储位置, 每个容器的数据保存路径可单独修改|
|./services/|服务根目录, Dockfile和服务配置均放在该目录|
|./docker-compose.dev.yml|php+go+python3+rust+mysql+redis+mongo等服务|
|./docker-compose.halo.yml|halo2|
|./docker-compose.memos.yml|memos|
|./docker-compose.nas.yml|emby+qbittorrent+nas-tools|
|./.dev.bashrc|包含快捷进入容器和执行容器内部命令的bash配置|
|./env.sample|环境变量配置|

## 使用方法

- 拉取代码: `git clone https://github.com/Lweiwen/lazy-docker.git ~/; cd ~/ds;`
- 复制默认环境变量配置:`cp .env.sample .env`, 自行按需修改。
- 添加快捷命令到.zshrc:  `echo "source ~/ds/.dev.bashrc" >> ~/.zshrc;` 或者.bashrc:`echo "source ~/ds/.dev.bashrc" >> ~/.bashrc;`, 或者只复制需要的命令函数到你的终端配置文件中。
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
|dpython3|进入python3容器|
|dmongo5|进入Mongodb5容器|
|dnginx|进入nginx容器|
|drust|进入rust容器|
|rustup|执行russ容器的rustup命令|
|cargo|执行rust容器的cargo命令|
|python3|执行python3容器的python命令|
|pip3|执行python3容器的pip命令|
|dnode16|进入node 16容器|
|dnode18|进入node 18容器|
|node16|执行16.x版本的node|
|node18|执行18.x版本的node|
|npm16|执行16.x版本node对应的npm|
|npm18|执行16.x版本node对应的npm|
|dgo|进入go容器|
|go|执行容器的go命令|
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

- 代码中连接mongodb
```
mongodb_host='mongo5'
mongodb_port=27017
```
