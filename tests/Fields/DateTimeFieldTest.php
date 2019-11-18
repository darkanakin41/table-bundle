<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\BooleanField;
use Darkanakin41\TableBundle\Fields\CountryField;
use Darkanakin41\TableBundle\Fields\DateTimeField;
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
