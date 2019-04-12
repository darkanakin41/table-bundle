<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\ImageField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ImageFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new ImageField($fieldname);
        $this->assertEquals("image", $field->getBlock());
        $this->assertCount(1, $field->getClasses());
        $this->assertTrue(in_array("text-center", $field->getClasses()));
        $this->assertTrue($field->isDisplayed());
    }

}
