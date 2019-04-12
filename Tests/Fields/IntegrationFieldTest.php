<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\ImageField;
use PLejeune\TableBundle\Fields\IntegrationField;
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
