<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\MapField;
use Darkanakin41\TableBundle\Fields\NumberField;
use Darkanakin41\TableBundle\Fields\StarField;
use PHPUnit\Framework\TestCase;

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
