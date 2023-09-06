# mingalevme/php-cache

https://github.com/php-cache/cache with:
- psr/log: "^1.0 || ^2.0 || ^3.0"
- psr/simple-cache: "^1.0 || ^2.0 || ^3.0"
- psr/cache: "^1.0 || ^2.0 || ^3.0"
- league/flysystem: "^2.0 || ^3.0"

# Testing

### APCu

```shell
pecl install apcu
```

### Memcached

```shell
brew install zlib
brew install libmemcached
pecl install memcached
docker run --rm -d -p 11211:11211 memcached:alpine
```

### MongoDB

```shell
pecl install mongodb
docker run --rm -d -p 27017:27017 mongo
```

### Redis

```shell
pecl install redis
docker run --rm -d -p 27017:27017 mongo
```

### Redis Cluster

```shell
php -m | grep -i redis > /dev/null || pecl install redis
docker run --rm -d -p 7000-7005:7000-7005 grokzen/redis-cluster:7.0.10
```

## PHP7.4

```shell
ulimit -n 65536
IS_REDIS_CLUSTER_ENABLED=0 /usr/local/opt/php@8.0/bin/php -d apc.enable_cli=1 -d memory_limit=512M ./vendor/bin/phpunit
```

## PHP8.0

```shell
ulimit -n 65536
IS_REDIS_CLUSTER_ENABLED=0 /usr/local/opt/php@8.0/bin/php -d apc.enable_cli=1 -d memory_limit=512M ./vendor/bin/phpunit
```

In case of
`Warning: include(some.php): failed to open stream: Too many open files in .../php-cache/vendor/composer/ClassLoader.php on line XXX`
run
`ulimit -n 65536`
before the script.

# PHP Cache

[![Gitter](https://badges.gitter.im/php-cache/cache.svg)](https://gitter.im/php-cache/cache?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)
[![codecov.io](https://codecov.io/github/php-cache/cache/coverage.svg?branch=master)](https://codecov.io/github/php-cache/cache?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![StyleCI](https://styleci.io/repos/50789153/shield)](https://styleci.io/repos/50789153)

This is the main repository for all our cache adapters and libraries. To read about
features like tagging and hierarchy support please read the shared documentation at [www.php-cache.com](http://www.php-cache.com).

Back in 2016, this was the first library supporting PSR-6. We created Symfony bundles
and made many great libraries in the PHP-cache organization. This was a few years ago
and the library is not activly maintained. Starting a new project one should consider
using the more performant and activly supported
[Symfony Cache](https://symfony.com/doc/current/components/cache.html).

### Notice

This library is for development use. All pull requests to the adapters, and libraries included in here, should be made to this library.

If you are lazy, you can also include this project, to include all the adapters and libraries.

### Contribute

Contributions are very welcome! Make sure you check out the [contributing docs](CONTRIBUTING.md) and send us a pull request or report any issues you find on the [issue tracker](http://issues.php-cache.com). Feel free to come chat with us on [Gitter](https://gitter.im/php-cache/cache?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge) too.
