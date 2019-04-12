<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\MapField;
use PLejeune\TableBundle\Fields\NumberField;
use PLejeune\TableBundle\Fields\StarField;
use PLejeune\TableBundle\Fields\UserField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class UserFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new UserField($fieldname);
        $this->assertEquals("user", $field->getBlock());
        $this->assertFalse($field->isSortable());
        $this->assertFalse($field->isFilterable());
        $this->assertCount(1, $field->getDisplayedAttributes());
        $this->assertTrue(in_array("firstname", $field->getDisplayedAttributes()));
    }

}
