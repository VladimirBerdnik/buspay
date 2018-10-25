#!/usr/bin/with-contenv bash

source /root/.bashrc
echo -e "${BLUE}--- Install composer dependencies ---${NC}"

COMPOSER_ALLOW_SUPERUSER=1
php composer.phar install --prefer-dist --no-interaction --optimize-autoloader --no-progress --no-suggest
