<?php


namespace Darkanakin41\TableBundle\Tests\Fields;

use Darkanakin41\TableBundle\Fields\MapField;
use Darkanakin41\TableBundle\Fields\NumberField;
use Darkanakin41\TableBundle\Fields\StarField;
use Darkanakin41\TableBundle\Fields\URLField;
use Darkanakin41\TableBundle\Fields\UserField;
use PHPUnit\Framework\TestCase;

class URLFieldTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $fieldname = "test";
        $field = new URLField($fieldname);
        $this->assertEquals("URL", $field->getBlock());
        $this->assertEquals("raw", $field->getSubBlock());
        $this->assertEmpty($field->getTarget());
        $this->assertEmpty($field->getLinkClasses());
        $this->assertFalse($field->isFilterable());

        $field->setLink("coucou-{toto}");
        $field->setLinkParams(['{toto}' => "block"]);

        $this->assertEquals("coucou-URL", $field->buildLink($field));
    }

}
