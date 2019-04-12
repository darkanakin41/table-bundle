<?php


namespace PLejeune\TableBundle\Tests\Fields;

use PLejeune\TableBundle\Fields\MapField;
use PLejeune\TableBundle\Fields\NumberField;
use PLejeune\TableBundle\Fields\StarField;
use PLejeune\TableBundle\Fields\URLField;
use PLejeune\TableBundle\Fields\UserField;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

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
