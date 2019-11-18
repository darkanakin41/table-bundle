<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\BooleanField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class BooleanFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new BooleanField($fieldname);
        $this->assertEquals("boolean", $field->getBlock());
        $this->assertCount(1, $field->getClasses());
        $this->assertTrue(in_array("text-center", $field->getClasses()));
        $this->assertTrue($field->isChoice());
        $this->assertTrue($field->isTranslation());
        $this->assertEquals("yes", $field->getValueToLabel(true));
        $this->assertEquals("no", $field->getValueToLabel(false));
    }

}
