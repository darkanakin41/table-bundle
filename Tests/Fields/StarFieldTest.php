<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\MapField;
use PLejeune\TableBundle\Fields\NumberField;
use PLejeune\TableBundle\Fields\StarField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class StarFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new StarField($fieldname);
        $this->assertEquals("star", $field->getBlock());
        $this->assertCount(2, $field->getClasses());
        $this->assertTrue(in_array("star", $field->getClasses()));
        $this->assertTrue(in_array("text-right", $field->getClasses()));
    }

}
