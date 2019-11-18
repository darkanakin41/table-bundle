<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\ArrayField;
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
