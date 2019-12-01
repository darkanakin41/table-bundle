<?php


namespace Darkanakin41\TableBundle\Tests\Definition;


use Darkanakin41\TableBundle\Definition\Action;
use Darkanakin41\TableBundle\Definition\Filter;
use Darkanakin41\TableBundle\Definition\Jointure;
use PHPUnit\Framework\TestCase;

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
