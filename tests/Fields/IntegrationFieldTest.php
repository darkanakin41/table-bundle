<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\ImageField;
use Darkanakin41\TableBundle\Fields\IntegrationField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class IntegrationFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new IntegrationField($fieldname);
        $this->assertEquals("integration", $field->getBlock());
        $this->assertFalse($field->isFilterable());
        $this->assertFalse($field->isSortable());
    }

}
