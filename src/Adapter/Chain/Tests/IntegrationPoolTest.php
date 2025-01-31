<?php

/*
 * This file is part of php-cache organization.
 *
 * (c) 2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Chain\Tests;

use Cache\Adapter\Chain\CachePoolChain;
use Tests\Cache\CachePoolTest;

class IntegrationPoolTest extends CachePoolTest
{
    use CreatePoolTrait;

    protected $skippedTests = [
        'testBasicUsageWithLongKey' => 'Long keys are not supported.',
    ];

    public function createCachePool()
    {
        return new CachePoolChain($this->getAdapters());
    }
}
