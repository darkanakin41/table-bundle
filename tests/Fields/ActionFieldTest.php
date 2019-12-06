<?php


namespace Darkanakin41\TableBundle\Tests\Fields;


use Darkanakin41\TableBundle\Fields\ActionField;
use Exception;
use PHPUnit\Framework\TestCase;

class ActionFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new ActionField($fieldname);
        $this->assertEquals("action", $field->getBlock());
        $this->assertTrue(in_array(sprintf('{%s}', $fieldname), $field->getAttributes()));
        $this->assertEquals("", $field->getButtonLabel());
    }

    /**
     * @depends testSimpleInstanciation
     * @throws Exception
     */
    public function testAttribute()
    {
        $fieldname = "block";
        $attributeValue = "block";
        $field = new ActionField($fieldname);
        $field->addAttribute("test", "{$attributeValue}");
        $this->assertTrue(in_array(sprintf('%s', $attributeValue), $field->getAttributes()));
        $this->assertEquals($attributeValue, $field->buildAttribute("test", $field));
    }

}
