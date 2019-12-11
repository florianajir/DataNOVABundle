<?php

namespace Fmaj\LaposteDatanovaBundle\Tests\DependencyInjection;

use Fmaj\LaposteDatanovaBundle\DependencyInjection\FmajLaposteDatanovaExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Florian Ajir <florianajir@gmail.com>
 */
class FmajLaposteDatanovaExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FmajLaposteDatanovaExtension
     */
    private $extension;

    /**
     * Root name of the configuration
     *
     * @var string
     */
    private $root;

    /**
     * @return FmajLaposteDatanovaExtension
     */
    protected function getExtension()
    {
        return new FmajLaposteDatanovaExtension();
    }

    /**
     * @return ContainerBuilder
     */
    private function getContainer()
    {
        $container = new ContainerBuilder();

        return $container;
    }

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->extension = $this->getExtension();
        $this->root = 'data_nova';
    }

    /**
     *
     */
    public function testGetConfigWithDefaultValues()
    {
        $this->extension->load(array(), $container = $this->getContainer());

        $this->assertTrue($container->has('data_nova.manager.records'));
    }
}
