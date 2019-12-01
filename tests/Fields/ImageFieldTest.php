<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\ImageField;
use PHPUnit\Framework\TestCase;

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
