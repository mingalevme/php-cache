<?php

declare(strict_types=1);

namespace Tests\Cache;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        error_reporting(E_ALL ^ E_DEPRECATED);
        parent::setUpBeforeClass();
    }
}
