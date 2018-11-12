# Synopsis
This is backend on **PHP / [Laravel 5.6](https://laravel.com/docs/5.5)**
for BusPay project

# Development
You have 2 options to run project locally:

1. Run and develop in isolated environment, using Docker (recommended)
2. Develop with full stack

## Run inside Docker

### Docker Quick Start
*(see explanations in **Run Inside Docker** section)*

```
mkdir buspay && cd buspay
git clone {repository url}
cd backend

make
make shell
artisan db:seed
```

#### CLI Shortcuts
Some frequent commands have shortcuts in *Makefile*, which allow to use ```make <shrtct>```
instead of long command. See [Makefile](Makefile) content for full list of shortcuts.
Here are some of them:

* **make install** - installs project prerequisites and dependencies:
 NodeJS, required global and local NPM packages, Composer packages, etc.
* **make static** - bundles JavaScript, styles and images from source,
human-readable files in ```resources\assets``` folder to minified, optimized for
download over internet files in ```public\assets``` folder.
* **make watch** - bundle static files and start LiveReload - monitoring
changes in bundled sources - if any of JS or style files is updated,
rebuild bundled JS/CSS files,  notify browser about changes, so that developer
could see changes in browser immediately.
* **make build** - builds Docker image for local development (see *Run inside Docker* below)
* **make run** - start Docker environment, consisting of several Docker containers:
DB Server, Redis Server, Nginx + php-fpm Web Server.
* **make stop** - stop all Docker containers (DB content, temporary files,
logs, etc. will remain intact, only running processes are stopped)
* **make destroy** - delete all docker containers (DB content,
temporary files, etc. will be erased)
* **make logs** - show logs of Docker container with web server
* **make shell** - start interactive BASH shell inside Docker container
with web server, using *nginx* user permissions.
* **make root** - start interactive BASH shell inside Docker container
with web server, using *root* user permissions.
* **make all** (or **make** without parameters) run described above
**install, build, static, run** tasks sequentially - thus,
install all required dependencies, assemble and start application in Docker.

### Checkout project

```bash
mkdir buspay && cd buspay
git clone {repository url}
cd backend
```

### Prerequisites
1. Install [Docker](http://docker.io) and [Docker Compose](https://docs.docker.com/compose/).
   See [docker/README.md](docker/README.md) for tips of Docker configuration.

2. Install PHP 7.1 or newer (ex. for Ubuntu)

    ```bash
    sudo add-apt-repository -y ppa:ondrej/php
    sudo apt-get update -y
    sudo apt-get install php7.1
    ```

3. Make sure, that you have [GNU Make](https://www.gnu.org/software/make/)
    utility installed (ex. check ```make --version```)
    Install it, if needed (ex. for Ubuntu):
    ```
    sudo apt install make
    ```

4. Install all other prerequisites with command:
    ```
    make install
    ```
    This will run ```install.sh``` script, that will try to locate existing prerequisites,
    and attempt to install what is missing. It will install NodeJS runtime and NPM packages,
    required to assemble static files (concatenate and minify source JS, styles, fonts
    and images), PHP Composer packages, and GIT hook, that restricts commits,
    if JavaScript, CSS and PHP linters say, that code violates code style rules,
    defined in project.

### Run application with single command

#### Start application

```
make
```

This will sequentially execute following 'make' targets:
**install** (see description above), **build, static, run** (see descriptions below).
That is enough to get running instance of application.

Wait for missing components download and configuration process to complete
(first run may take up to 10-20 min, subsequent runs will be much faster).


Check, if containers are running:

```
docker ps
```
There should be: buspay-web, buspay-db, buspay-redis

#### Learn your backend container IP

```
make ip
```

(*shortcut for ```docker inspect buspay-web | grep IPAddress```*) -
use this IP address to make HTTP requests, for example, with [Postman](http://getpostman.com) utility
 (see *Artifacts/Postman* directory)

#### Get shell inside Docker
You can get interactive shell inside docker container with web application

```
make shell
```
(*shortcut for ```docker-compose exec web bash```*) - this way you can execute
shell commands inside container, ex. artisan commands to manually run
DB migrations, clear/update caches for config, views, routes, etc. that
will respect paths inside Docker container and Docker's service discovery
across all docker containers, that belong to this application, and form
local environment - ex. DB server, Redis server etc.

Ex. ```ping db``` inside container will show you actual IP address of DB server.,
and ```ping redis``` will let you know redis server address.

If you need root access (for example to install additional packages,
override files ownership, etc.),
run

```
make root
```

#### Fill in your database with randomly generated data

Use ```make shell``` to get inside container (see explanation above), then
```
artisan db:seed
```
You can also run [seeders](https://laravel.com/docs/seeding) together or
[individually](https://laravel.com/docs/seeding#running-seeders)
to add more random data afterwards.

### Local environment management
Stop, resume, destroy and recreate whole application environment

#### Stop whole environment
```
make stop
```
(*alias for ```docker-compose kill```*)
This will stop all Docker containers - DB content, temporary files,
logs, etc. will remain intact, only running processes are stopped.
You can resume application execution later, with all previously entered
accounts, content, etc.

*Use this, for example, to pause work at project and release runtime resources,
 switch to work at another project, and resume work later.*

#### Destroy whole environment
```
make destroy
```
(*alias for ```docker-compose down```*)
This will delete all docker containers - DB content, temporary files, etc.
will be erased permanently - this will free space, consumed by containers.

*Use this, if you do not plan to work with project anymore,
or you troubleshoot and need to recreate everything from scratch.*

#### Start / Resume environment
```
make run
```
(*alias for ```docker-compose up```*)
*Use this to start project after 'stop' or 'destroy'.*

This will:

* Create/Resume Web app, DB, Redis containers (download them, if needed)
* Install composer dependencies
* Create *.env* file, if it doesn't exist yet (copy from *.env.example* template)
* Cache config, routes, api endpoints
* Apply Laravel DB migrations, that were not applied yet
* Start [Laravel queue worker](https://laravel.com/docs/queues#running-the-queue-worker)
    inside web application container
    (Developer can just push new jobs to default queue, and they will be processed)
* Start *cron* inside Docker container, that runs [Laravel's schedule command](https://laravel.com/docs/5.5/scheduling) every minute
    (Developer can define Laravel's Schedules - they will just work)

```
make build
```
(*alias for ```docker-compose build --no-cache web```*)
This will download required Docker images, build docker container for web application.
Inside container you will have consistent set and configuration of
PHP (with all needed modules), Nginx, PHP-FPM.

*You may need to run this command, if server configuration was changed.
Ex. DevOps changed Nginx config, or new PHP modules were added,
or scripts, that run on container start were updated*

### Recommendations

When you switch branches, drop and recreate the whole environment,
fill it in with sample data:

```
make destroy
make run
make shell
artisan db:seed
```
This will allow you to have DB version, consistent with another branch
(another branch may require another DB structure - and additional or
missing DB migrations).

#### Linters
Before commit run linters to check, if your PHP, JavaScript code
matches code style rules (
[PSR-2](www.php-fig.org/psr/psr-2/) for PHP,
[Airbnb](https://github.com/airbnb/javascript) for JS
), verified by [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
and [ESLint](https://eslint.org/):

```
make cs
make lint
```

Some violations can be fixed automatically (ex. code formatting)

```
make csfix
make lintfix
```

## Develop with full stack locally
**If you use Docker, see manual above**

Requirements:
* [PHP](https://php.net) 7.1+
* [MySQL](https://www.mysql.com) 5.7+
* [Node.JS](https://nodejs.org) 6.11+ - JavaScript runtime
* [YARN](https://yarnpkg.com) 1.9+ -  Node.JS package manager
* [GNU Make](https://www.gnu.org/software/make/) - build tool

Install all required software from above list, then run:

```
cd buspay
make install
make static
```

Create Laravel's configuration file:

```
cp .env.example .env
```

Create empty MySQL database and set proper DB_* variable in **.env** file,
then create DB structure for current application version
(or update existing DB structure to current version):

```
php artisan migrate
```

Fill in database with some random values for testing:

```
php artisan db:seed
```
