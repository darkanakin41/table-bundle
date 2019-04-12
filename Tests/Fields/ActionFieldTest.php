<?php


namespace PLejeune\TableBundle\Tests\Fields;


use PLejeune\TableBundle\Fields\ActionField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ActionFieldTest extends TestCase
{
    public function testSimpleInstanciation(){
        $fieldname = "test";
        $field = new ActionField($fieldname);
        $this->assertEquals("action", $field->getBlock());
        $this->assertEmpty($field->getAttributes());
        $this->assertEquals("", $field->getButtonLabel());
    }

    public function testAttribute(){
        $fieldname = "block";
        $attributeValue = "block";
        $field = new ActionField($fieldname);
        $field->addAttribute("test", "{$attributeValue}");
        $this->assertTrue(in_array("{$attributeValue}", $field->getAttributes()));
        $this->assertEquals($attributeValue, $field->buildAttribute("test",$field));
    }

}