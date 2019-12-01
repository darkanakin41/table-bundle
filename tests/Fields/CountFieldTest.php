<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\CountField;
use PHPUnit\Framework\TestCase;

class CountFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new CountField($fieldname);
        $this->assertEquals("count", $field->getBlock());
        $this->assertFalse($field->isFilterable());
        $this->assertFalse($field->isSortable());
    }

}
