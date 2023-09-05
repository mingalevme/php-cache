<?php

/*
 * This file is part of php-cache organization.
 *
 * (c) 2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Namespaced\Tests;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Cache\Hierarchy\HierarchicalPoolInterface;
use Cache\Namespaced\NamespacedCachePool;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class IntegrationTest extends TestCase
{
    /**
     * @type CacheItemPoolInterface|HierarchicalPoolInterface
     */
    private $cache;

    protected function setUp(): void
    {
        $cache = new ArrayCachePool();

        $this->cache = $cache;
    }

    protected function tearDown(): void
    {
        if ($this->cache !== null) {
            $this->cache->clear();
        }
    }

    public function testGetItem()
    {
        $namespace = 'ns';
        $nsPool = new NamespacedCachePool($this->cache, $namespace);

        $item = $nsPool->getItem('key');
        $this->assertEquals("|$namespace|key", $item->getKey());
    }

    public function testGetItems()
    {
        $namespace = 'ns';
        $nsPool = new NamespacedCachePool($this->cache, $namespace);

        $items = $nsPool->getItems(['key0', 'key1']);

        $str = "|$namespace|key0";
        $this->assertTrue(isset($items[$str]));
        $this->assertEquals($str, $items[$str]->getKey());

        $str = "|$namespace|key1";
        $this->assertTrue(isset($items[$str]));
        $this->assertEquals($str, $items[$str]->getKey());
    }

    public function testSave()
    {
        $namespace = 'ns';
        $nsPool = new NamespacedCachePool($this->cache, $namespace);

        $item = $nsPool->getItem('key');
        $item->set('foo');
        $nsPool->save($item);

        $this->assertTrue($nsPool->hasItem('key'));
        $this->assertFalse($this->cache->hasItem('key'));
    }

    public function testSaveDeferred()
    {
        $namespace = 'ns';
        $nsPool = new NamespacedCachePool($this->cache, $namespace);

        $item = $nsPool->getItem('key');
        $item->set('foo');
        $nsPool->saveDeferred($item);

        $this->assertTrue($nsPool->hasItem('key'));
        $this->assertFalse($this->cache->hasItem('key'));

        $nsPool->commit();
        $this->assertTrue($nsPool->hasItem('key'));
        $this->assertFalse($this->cache->hasItem('key'));
    }
}
