<?php

namespace Fmaj\LaposteDatanovaBundle\Tests;

use Fmaj\LaposteDatanovaBundle\FmajLaposteDatanovaBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LaposteDatanovaBundleTest extends TestCase
{
    public function testBuild()
    {
        $bundle = new FmajLaposteDatanovaBundle();
        $container = new ContainerBuilder();

        $bundle->build($container);
        $this->assertNotNull($bundle);
    }
}
