#!/usr/bin/with-contenv bash

source /root/.bashrc
echo -e "${BLUE}--- Creating '${APP_ENV}' environment configuration ---${NC}"

echo Generate Laravel .env file and remove other configs
cp -f .env.${APP_ENV} .env
rm .env.*

## Development and staging environments should not be scanned by web-crowlers
if [[ "$APP_ENV" != "production" ]]; then
  echo "create robots.txt"
  echo -e "User-agent: *\nDisallow: /" > public/robots.txt
fi
