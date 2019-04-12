<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\CountField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class CountFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new CountField($fieldname);
        $this->assertEquals("count", $field->getBlock());
        $this->assertFalse($field->isFilterable());
        $this->assertFalse($field->isSortable());
    }

}
