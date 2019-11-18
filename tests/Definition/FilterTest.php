<?php


namespace Darkanakin41\TableBundle\Tests\Definition;


use Darkanakin41\TableBundle\Definition\Field;
use Darkanakin41\TableBundle\Definition\Filter;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class FilterTest extends TestCase
{
    public function testSimpleInstanciation()
    {

        $fieldname = "test";
        $field = new Field($fieldname);

        $filterValue = 'value-test';
        $filter = new Filter($field, $filterValue);
        $this->assertEquals($fieldname, $filter->getField()->getLabel());
        $this->assertEquals($filterValue, $filter->getValue());
        $this->assertFalse($filter->isNot());

        $expectedAlias = $field->getId()."_alias";
        $this->assertEquals($expectedAlias, $filter->getAlias("alias"));

        $expectedDQL = sprintf("%s = :%s_%s", $field->getDQL("a"), $field->getId(), "thekey");
        $this->assertEquals($expectedDQL, $filter->getDQL("thekey", "a"));

        $filter->setNot(true);
        $expectedDQL = sprintf("%s <> :%s_%s", $field->getDQL("a"), $field->getId(), "thekey");
        $this->assertEquals($expectedDQL, $filter->getDQL("thekey", "a"));

        $dqlParameters = $filter->getDQLParameters("alias");
        $this->assertArrayHasKey($expectedAlias, $dqlParameters);
        $this->assertEquals($filter->getValue(), $dqlParameters[$expectedAlias]);
    }

}
