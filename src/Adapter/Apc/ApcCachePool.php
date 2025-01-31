<?php

/*
 * This file is part of php-cache organization.
 *
 * (c) 2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Apc;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Common\PhpCacheItem;
use Cache\Adapter\Common\TagSupportWithArray;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ApcCachePool extends AbstractCachePool
{
    use TagSupportWithArray;

    /**
     * @type bool
     */
    private $skipOnCli;

    /**
     * @param bool $skipOnCli
     */
    public function __construct($skipOnCli = false)
    {
        $this->skipOnCli = $skipOnCli;
    }

    /**
     * {@inheritdoc}
     */
    protected function fetchObjectFromCache($key): array
    {
        if ($this->skipIfCli()) {
            return [false, null, [], null];
        }

        $success   = false;
        $cacheData = apc_fetch($key, $success);
        if (!$success) {
            return [false, null, [], null];
        }
        [$data, $tags, $timestamp] = unserialize($cacheData);

        return [$success, $data, $tags, $timestamp];
    }

    /**
     * {@inheritdoc}
     */
    protected function clearAllObjectsFromCache(): bool
    {
        return apc_clear_cache('user');
    }

    /**
     * {@inheritdoc}
     */
    protected function clearOneObjectFromCache($key): bool
    {
        apc_delete($key);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function storeItemInCache(PhpCacheItem $item, $ttl): bool
    {
        if ($this->skipIfCli()) {
            return false;
        }

        if ($ttl < 0) {
            return false;
        }

        return apc_store($item->getKey(), serialize([$item->get(), $item->getTags(), $item->getExpirationTimestamp()]), $ttl);
    }

    /**
     * Returns true if CLI and if it should skip on cli.
     *
     * @return bool
     */
    private function skipIfCli(): bool
    {
        return $this->skipOnCli && php_sapi_name() === 'cli';
    }

    /**
     * {@inheritdoc}
     */
    public function getDirectValue($name)
    {
        return apc_fetch($name);
    }

    /**
     * {@inheritdoc}
     */
    public function setDirectValue($name, $value)
    {
        apc_store($name, $value);
    }
}
