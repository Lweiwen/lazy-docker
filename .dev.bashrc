# PHP
alias dphp='docker exec -it php /bin/sh'

# MYSQL
alias dmysql='docker exec -it mysql /bin/bash'
function mysql() {
  docker exec -it mysql mysql "$@"
}

# REDIS
alias dredis='docker exec -it redis7 /bin/sh'
function redis-cli() {
  docker exec -it redis7 redis-cli "$@"
}

# Nginx
alias dnginx='docker exec -it nginx /bin/sh'
function nginx() {
  docker exec -it nginx nginx "$@"
}

# php82 composer
function php() {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php82 php "$@"
}

function composer() {
  docker run -it --rm --volume $PWD:/www:rw --workdir /www ld-php82 composer "$@"
}