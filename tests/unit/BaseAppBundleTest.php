<?php

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use xVer\Symfony\Bundle\BaseAppBundle\BaseAppBundle;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\BaseAppBundle
 */
class BaseAppBundleTest extends TestCase
{
    public function testGetPath(): void
    {
        $bb = new BaseAppBundle();
        $this->assertNotEmpty($bb->getPath());
    }
}