# PHP
alias dphp72='docker exec -it php72 /bin/sh'
alias dphp73='docker exec -it php73 /bin/sh'
alias dphp74='docker exec -it php74 /bin/sh'
alias dphp80='docker exec -it php80 /bin/sh'
alias dphp81='docker exec -it php81 /bin/sh'
alias dphp82='docker exec -it php82 /bin/sh'

# MYSQL
alias dmysql5='docker exec -it mysql5 /bin/bash'
alias dmysql8='docker exec -it mysql8 /bin/bash'
mysql5 () {
  docker exec -it mysql5 mysql "$@"
}

# REDIS
alias dredis5='docker exec -it redis5 /bin/sh'
alias dredis6='docker exec -it redis6 /bin/sh'
alias dredis7='docker exec -it redis7 /bin/sh'
redis5-cli () {
  docker exec -it redis5 redis-cli "$@"
}
redis6-cli () {
  docker exec -it redis6 redis-cli "$@"
}
redis7-cli () {
  docker exec -it redis7 redis-cli "$@"
}

# MongoDB
alias dmongo5='docker exec -it mongo5 /bin/bash'

# Nginx
alias dnginx='docker exec -it nginx /bin/sh'
nginx () {
  docker exec -it nginx nginx "$@"
}


# php72 composer
php72 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php72 php "$@"
}
composer72 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php72 composer "$@"
}

# php73 composer
php73 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php73 php "$@"
}
composer73 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php73 composer "$@"
}

# php74 composer
php74 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php74 php "$@"
}
composer74 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php74 composer "$@"
}

# php80 composer
php80 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php80 php "$@"
}
composer80 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php80 composer "$@"
}

# php81 composer
php81 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php81 php "$@"
}
composer81 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php81 composer "$@"
}

# php82 composer
php82 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php82 php "$@"
}
composer82 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php82 composer "$@"
}


# node16
alias dnode16='docker exec -it node16 /bin/sh'
node16 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www node16 node "$@"
}
npm16 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www node16 npm "$@"
}

# node18
alias dnode16='docker exec -it node16 /bin/sh'
node18 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www node18 node "$@"
}
npm18 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www node18 npm "$@"
}


# go
alias dgo='docker exec -it go /bin/sh'
go () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www go go "$@"
}

# # rust
# alias drust='docker exec -it rust /bin/sh'
# rustup () {
#     docker exec -it rust sh -c  "cd /www/$(basename $PWD) && rustup $@"
# }
# cargo () {
#     docker exec -it rust sh -c  "cd /www/$(basename $PWD) && cargo $@"
# }