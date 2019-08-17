<?php

namespace Bankin\Bundle\Tests;

use Bankin\Bundle\BankinBundle;
use Bankin\Bundle\DependencyInjection\BankinExtension;
use PHPUnit\Framework\TestCase;

class BankinBundleTest extends TestCase
{
    public function testBundleName()
    {
        $bundle = new BankinBundle();

        $this->assertEquals('BankinBundle', $bundle->getName());
    }

    public function testBundleLoadExtension()
    {
        $bundle = new BankinBundle();

        $this->assertInstanceOf(BankinExtension::class, $bundle->getContainerExtension());
    }
}