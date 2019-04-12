<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\BooleanField;
use PLejeune\TableBundle\Fields\CountryField;
use PLejeune\TableBundle\Fields\DateTimeField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Validator\Constraints\Country;

class DateTimeFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new DateTimeField($fieldname);
        $this->assertEquals("datetime", $field->getBlock());
        $this->assertCount(1, $field->getClasses());
        $this->assertTrue(in_array("text-right", $field->getClasses()));
        $this->assertEquals("d/m/Y \à H:i", $field->getFormat());
    }

}
