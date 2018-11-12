all: install build static run

install:
	bash install.sh

build:
	docker-compose build --no-cache --build-arg hostUID=`id -u` --build-arg hostGID=`id -g` web

start: run

run:
	docker-compose -p buspay up -d web

stop:
	docker-compose -p buspay kill

destroy:
	docker-compose -p buspay down

logs:
	docker-compose -p buspay logs web

shell:
	docker-compose -p buspay exec --user nginx web bash

root:
	docker-compose -p buspay exec web bash

cs:
	php vendor/bin/phpcs

csfix:
	php vendor/bin/phpcbf

test:
	php vendor/bin/phpunit

static:
	yarn run development

watch:
	yarn run watch

lint:
	yarn run lint

lintfix:
	yarn run lint:fix

ip:
	docker inspect buspay-web | grep \"IPAddress\"
