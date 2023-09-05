<?php

namespace Tests\Cache;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Common\Exception\CachePoolException;
use Cache\Adapter\Common\PhpCacheItem;
use ErrorException;
use Exception;
use InvalidArgumentException;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use RuntimeException;

final class LoggingTest extends AbstractTestCase
{
    public function test(): void
    {
        $logger = new class extends AbstractLogger {
            /** @var list<array{level: string, message: string, context: array}> */
            private array $history = [];

            public function log($level, $message, array $context = []): void
            {
                $this->history[] = [
                    'level' => $level,
                    'message' => $message,
                    'context' => $context,
                ];
            }

            /**
             * @return list<array{level: string, message: string, context: array}>
             */
            public function getHistory(): array
            {
                return $this->history;
            }

            /**
             * @return array{level: string, message: string, context: array}
             */
            public function getLastHistoryEntry(): array
            {
                if (!$this->history) {
                    throw new RuntimeException('History is empty');
                }

                return $this->history[array_key_last($this->history)];
            }
        };

        $pool = $this->getPool();
        $pool->setLogger($logger);
        $pool->setDefaultLogLevel(LogLevel::CRITICAL);

        $pool->setExceptionToLogLevelMap([
            RuntimeException::class => LogLevel::DEBUG,
            Exception::class => LogLevel::ERROR,
        ]);

        try {
            $pool->set('foo', 'bar');
            self::fail('Exception has not been thrown');
        } catch (CachePoolException $e) {
            self::assertInstanceOf(ErrorException::class, $e->getPrevious());
        }

        self::assertCount(1, $logger->getHistory());
        self::assertSame(LogLevel::CRITICAL, $logger->getLastHistoryEntry()['level']);

        try {
            $pool->clear();
            self::fail('Exception has not been thrown');
        } catch (CachePoolException $e) {
            self::assertInstanceOf(RuntimeException::class, $e->getPrevious());
        }

        self::assertCount(2, $logger->getHistory());
        self::assertSame(LogLevel::DEBUG, $logger->getLastHistoryEntry()['level']);

        try {
            $pool->get('foo');
            self::fail('Exception has not been thrown');
        } catch (CachePoolException $e) {
            self::assertInstanceOf(InvalidArgumentException::class, $e->getPrevious());
        }

        self::assertCount(3, $logger->getHistory());
        self::assertSame(LogLevel::CRITICAL, $logger->getLastHistoryEntry()['level']);
    }

    private function getPool(): AbstractCachePool
    {
        $pool = new class extends AbstractCachePool {

            protected function storeItemInCache(PhpCacheItem $item, $ttl): bool
            {
                throw new ErrorException();
            }

            protected function fetchObjectFromCache($key): array
            {
                throw new InvalidArgumentException();
            }

            protected function clearAllObjectsFromCache(): bool
            {
                $this->throwNotImplemented();
            }

            protected function clearOneObjectFromCache($key): bool
            {
                $this->throwNotImplemented();
            }

            protected function getList($name): array
            {
                $this->throwNotImplemented();
            }

            protected function removeList($name): bool
            {
                $this->throwNotImplemented();
            }

            protected function appendListItem($name, $key)
            {
                $this->throwNotImplemented();
            }

            protected function removeListItem($name, $key)
            {
                $this->throwNotImplemented();
            }

            private function throwNotImplemented(): void
            {
                throw new RuntimeException('Not implemented');
            }
        };

        $pool->setExceptionToLogLevelMap([
            RuntimeException::class => LogLevel::DEBUG,
            Exception::class => LogLevel::ERROR,
        ]);

        return $pool;
    }
}
