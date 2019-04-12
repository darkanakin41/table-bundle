<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\MapField;
use PLejeune\TableBundle\Fields\NumberField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class NumberFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new NumberField($fieldname);
        $this->assertCount(1, $field->getClasses());
        $this->assertTrue(in_array("text-right", $field->getClasses()));
        $this->assertTrue($field->isNumeric());
    }

}
