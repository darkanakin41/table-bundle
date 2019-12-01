<?php


namespace Darkanakin41\TableBundle\Tests\DependencyInjection;


use Darkanakin41\TableBundle\DependencyInjection\Darkanakin41TableExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Darkanakin41TableExtensionTest extends TestCase
{
    public function testLoadConfiguration()
    {
        $configuration = new ContainerBuilder();
        $loader = new Darkanakin41TableExtension();
        $loader->load([], $configuration);
        $bundleConfig = $configuration->getParameter('darkanakin41.table.config');
        $this->assertNotEmpty($bundleConfig);
        $this->assertArrayHasKey('template', $bundleConfig);
        $this->assertArrayHasKey('table', $bundleConfig['template']);
        $this->assertArrayHasKey('fields', $bundleConfig['template']);
    }
}
