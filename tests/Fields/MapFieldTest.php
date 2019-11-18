<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\MapField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class MapFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new MapField($fieldname);
        $this->assertEquals("map", $field->getBlock());
        $this->assertFalse($field->isFilterable());
        $this->assertFalse($field->isSortable());
    }
    public function testGetValue()
    {
        $fieldname = "test";
        $field = new MapField($fieldname);
        $this->assertEquals("toto", $field->getValue("toto"));
        $field->setKeyValues(['toto','tata']);
        $this->assertEquals("titi", $field->getValue(["toto" => "titi"]));
        $this->assertNull($field->getValue(["zaza" => "titi"]));
    }

}
