<?php


namespace PLejeune\TableBundle\Tests\Definition;


use PLejeune\TableBundle\Definition\Action;
use PLejeune\TableBundle\Definition\Filter;
use PLejeune\TableBundle\Definition\Jointure;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class JointureTest extends TestCase
{
    public function testSimpleInstanciation()
    {
        $jointureId = "test-jointure";
        $jointureField = "jointure";

        $jointure = new Jointure(Action::class, $jointureField, $jointureId);

        $this->assertEquals(Action::class, $jointure->getClass());
        $this->assertEquals($jointureId, $jointure->getId());
        $this->assertEquals($jointureField, $jointure->getField());

        $this->assertEquals("a.$jointureField", $jointure->getDQL("a"));

        $jointureParent = new Jointure(Filter::class, $jointureField, $jointureId . "-parent");
        $jointure->setParent($jointureParent);

        $this->assertEquals($jointureParent->getId() .".$jointureField", $jointure->getDQL());
    }

}
