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
redis5-cli () {
  docker exec -it redis5 redis-cli "$@"
}

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

# php80 composer
php80 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php80 php "$@"
}
composer80 () {
    docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php80 composer "$@"
}

# php82 composer
php82 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php82 php "$@"
}
composer82 () {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php82 composer "$@"
}
