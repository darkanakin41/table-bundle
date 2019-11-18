<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\BooleanField;
use Darkanakin41\TableBundle\Fields\CountryField;
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
