#!/usr/bin/with-contenv bash

source /root/.bashrc
echo -e "${BLUE}--- Clear Laravel configuration cache---${NC}"

php artisan config:clear
php artisan route:clear
