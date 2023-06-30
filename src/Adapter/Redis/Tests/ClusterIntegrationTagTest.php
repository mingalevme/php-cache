<?php

/*
 * This file is part of php-cache organization.
 *
 * (c) 2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Redis\Tests;

use Tests\Cache\TaggableCachePoolTest;

class ClusterIntegrationTagTest extends TaggableCachePoolTest
{
    use CreateRedisClusterPoolTrait;
}
