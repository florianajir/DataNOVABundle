<?php

namespace Fmaj\LaposteDatanovaBundle\Tests;

use Fmaj\LaposteDatanovaBundle\FmajLaposteDatanovaBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 */
class LaposteDatanovaBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $bundle = new FmajLaposteDatanovaBundle();
        $container = new ContainerBuilder();

        $bundle->build($container);
    }
}
