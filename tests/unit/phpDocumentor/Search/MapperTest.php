<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Search;

/**
 * Tests the Mapper class for the Search Engine.
 *
 * @covers \phpDocumentor\Search\Mapper
 */
class MapperTest extends \PHPUnit_Framework_TestCase
{
    protected $definition = array('id' => 'data.id');

    /**
     * @covers \phpDocumentor\Search\Mapper::__construct
     * @covers \phpDocumentor\Search\Mapper::setDefinition
     */
    public function testInitializationWithDefinition()
    {
        $mapper = new Mapper($this->definition);
        $this->assertAttributeEquals($this->definition, 'definition', $mapper);
    }

    /**
     * @covers \phpDocumentor\Search\Mapper::getTwigEnvironment
     * @covers \phpDocumentor\Search\Mapper::setTwigEnvironment
     */
    public function testTwigEnvironment()
    {
        $mapper = new Mapper($this->definition);
        $this->assertInstanceOf('Twig_Environment', $mapper->getTwigEnvironment());

        $mock = $this->getMock('Twig_Environment');
        $mapper->setTwigEnvironment($mock);

        $this->assertSame($mock, $mapper->getTwigEnvironment());
    }

    public function testPopulate()
    {
        $mapper = new Mapper($this->definition);

        $mock = $this->getMock('Twig_Environment', array('render'));
        $mock->expects($this->once())->method('render')->with('data.id')->will($this->returnValue(1));
        $mapper->setTwigEnvironment($mock);

        $document = new Document();
        $object = new \stdClass();
        $object->id = '1';

        $mapper->populate($document, $object);

        $this->assertSame(1, $document->getId());
    }
}