<?php

/*
 * This file is part of php-cache organization.
 *
 * (c) 2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Void;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Common\PhpCacheItem;
use Cache\Hierarchy\HierarchicalPoolInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class VoidCachePool extends AbstractCachePool implements HierarchicalPoolInterface
{
    /**
     * {@inheritdoc}
     */
    protected function fetchObjectFromCache($key): array
    {
        return [false, null, [], null];
    }

    /**
     * {@inheritdoc}
     */
    protected function clearAllObjectsFromCache(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function clearOneObjectFromCache($key): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function storeItemInCache(PhpCacheItem $item, $ttl): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clearTags(array $tags)
    {
        return true;
    }

    protected function getList($name): array
    {
        return [];
    }

    protected function removeList($name): bool
    {
        return true;
    }

    protected function appendListItem($name, $key)
    {
    }

    protected function removeListItem($name, $key)
    {
    }
}
