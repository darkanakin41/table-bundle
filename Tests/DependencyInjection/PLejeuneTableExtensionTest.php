<?php


namespace PLejeune\TableBundle\Tests\DependencyInjection;


use PLejeune\TableBundle\DependencyInjection\PLejeuneTableExtension;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PLejeuneTableExtensionTest extends TestCase
{
    public function testLoadConfiguration()
    {
        $configuration = new ContainerBuilder();
        $loader = new PLejeuneTableExtension();
        $loader->load([], $configuration);
        $bundleConfig = $configuration->getParameter('plejeune.table.config');
        $this->assertNotEmpty($bundleConfig);
        $this->assertArrayHasKey('template', $bundleConfig);
        $this->assertArrayHasKey('table', $bundleConfig['template']);
        $this->assertArrayHasKey('fields', $bundleConfig['template']);
    }
}
