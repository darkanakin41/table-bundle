<?php


namespace PLejeune\TableBundle\Tests\Definition;


use PLejeune\TableBundle\Definition\Action;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ActionTest extends TestCase
{
    public function testSimpleInstanciation(){
        $actionLabel = "test";
        $action = new Action($actionLabel, 'test', ['test' => '12345']);
        $this->assertEquals($actionLabel, $action->getLabel());
        $this->assertEquals('test', $action->getRoute());
        $this->assertArrayHasKey('test', $action->getRouteParam());
        $this->assertEquals('12345', $action->getRouteParam()['test']);
    }

}
