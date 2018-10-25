RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

export TERM=xterm
# shell prompt: green host, blue current path
export PS1="\[$YELLOW\]\\h\[$NC\]\[$BLUE\] \\w \[$RED\]#\[$NC\] "
source /root/.aliases

function start-service()
{
  s6-svc -u /var/run/s6/services/${1}
  [[ $? -eq 0 ]] && echo -e "${1} ${GREEN}started ${NC}"
}

function stop-service()
{
  s6-svc -d /var/run/s6/services/${1}
  [[ $? -eq 0 ]] && echo -e "${1} ${RED}stopped ${NC}"
}
