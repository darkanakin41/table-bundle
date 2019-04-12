<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\BooleanField;
use PLejeune\TableBundle\Fields\CountryField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Validator\Constraints\Country;

class CountryFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new CountryField($fieldname);
        $this->assertEquals("raw", $field->getBlock());
    }

}
