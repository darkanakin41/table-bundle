<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\ArrayField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ArrayFieldTest extends TestCase
{
    public function testSimpleInstanciation(){
        $fieldname = "test";
        $field = new ArrayField($fieldname);
        $this->assertEquals("array", $field->getBlock());
        $this->assertEquals("raw", $field->getSubBlock());
        $this->assertEquals(",", $field->getSeparator());
    }

}
