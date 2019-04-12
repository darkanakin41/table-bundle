<?php


namespace PLejeune\TableBundle\Tests\Definition;


use PLejeune\TableBundle\Definition\Field;
use PLejeune\TableBundle\Definition\Jointure;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class FieldTest extends TestCase
{
    public function testSimpleInstanciation(){
        $fieldname = "test";
        $field = new Field($fieldname);
        $this->assertEquals($fieldname, $field->getField());
        $this->assertEquals($fieldname, $field->getId());
        $this->assertEquals($fieldname, $field->getLabel());
        $this->assertEquals("raw", $field->getBlock());
        $this->assertTrue($field->isFilterable());
        $this->assertFalse($field->isChoice());
        $this->assertTrue($field->isSortable());
        $this->assertTrue($field->isVisible());
        $this->assertFalse($field->isNumeric());
        $this->assertFalse($field->isTranslation());
        $this->assertNull($field->getTable());
        $this->assertEmpty($field->getValueToLabels());
        $this->assertNull($field->getTranslationPrefix());
        $this->assertNull($field->getJointure());
        $this->assertEquals("toto", $field->getValue("toto"));
    }

    public function testAdvancedInstanciation(){
        $fieldname = "test";
        $label = "toto";
        $id= "titi";
        $field = new Field($fieldname, $label, $id);
        $this->assertEquals($fieldname, $field->getField());
        $this->assertEquals($id, $field->getId());
        $this->assertEquals($label, $field->getLabel());
    }

    public function testValuesToLabel(){
        $fieldname = "test";
        $field = new Field($fieldname);
        $field->setValueToLabels(['TOTO' => 'titi', 'Foo' => 'bar']);
        $this->assertEquals("#N/C", $field->getValueToLabel(null));
        $this->assertEquals("unknown_value", $field->getValueToLabel("TOTO"));
        $this->assertEquals("titi", $field->getValueToLabel("toto"));
        $this->assertEquals("bar", $field->getValueToLabel("foo"));
    }

    public function testTranslationPrefix(){
        $fieldname = "test";
        $translationPrefix = "coucou";
        $field = new Field($fieldname);
        $field->setTranslationPrefix($translationPrefix);
        $this->assertTrue($field->isTranslation());
        $this->assertEquals($translationPrefix, $field->getTranslationPrefix());
    }

    public function testJointure(){
        $fieldname = "test";
        $jointure = new Jointure("test","test","test");
        $field = new Field($fieldname);
        $field->setJointure($jointure);
        $this->assertFalse($field->isSortable());
    }

    public function testClasses(){
        $fieldname = "test";
        $field = new Field($fieldname);
        $this->assertTrue(is_array($field->getClasses()));
        $field->addClasse("toto");
        $this->assertTrue(in_array('toto', $field->getClasses()));
    }

    public function testDQL(){
        $fieldname = "test";
        $field = new Field($fieldname);
        $this->assertEquals("e.$fieldname", $field->getDQL("e"));

        $jointure = new Jointure("test","test","test");
        $jointure->setId("toto");
        $field->setJointure($jointure);
        $this->assertEquals("toto.$fieldname", $field->getDQL("e"));
    }

    /**
     * TODO Creer le cas de test
     */
    public function testQBFilter(){
        $this->assertTrue(true);
    }
}
