<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\MapField;
use Darkanakin41\TableBundle\Fields\NumberField;
use Darkanakin41\TableBundle\Fields\StarField;
use Darkanakin41\TableBundle\Fields\UserField;
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
