<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
namespace phpDocumentor\Transformer\Writer;

use \phpDocumentor\Transformer\Transformer;

/**
 * Test class for \phpDocumentor\Transformer\Writer\Factory.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests whether the factory is correctly initialized.
     *
     * @covers phpDocumentor\Transformer\Writer\Factory::__construct
     * @covers phpDocumentor\Transformer\Writer\Factory::getTransformer
     *
     * @return void
     */
    public function testInitialization()
    {
        $transformer = $this->getMock('phpDocumentor\Transformer\Transformer');
        $factory = new Factory($transformer);

        $this->assertSame($transformer, $factory->getTransformer());
    }

    /**
     * Tests whether the factory can retrieve a registered writer.
     *
     * @covers phpDocumentor\Transformer\Writer\Factory::register
     * @covers phpDocumentor\Transformer\Writer\Factory::get
     *
     * @return void
     */
    public function testGettingAWriter()
    {
        $transformer = $this->getMock('phpDocumentor\Transformer\Transformer');
        $factory = new Factory($transformer);

        $factory->register('Xsl', 'phpDocumentor\Transformer\Writer\WriterMock');

        $this->assertInstanceOf(
            'phpDocumentor\Transformer\Writer\WriterMock',
            $factory->get('Xsl')
        );
        $this->assertSame($transformer, $factory->get('Xsl')->getTransformer());
    }
}

class WriterMock extends WriterAbstract
{
    public function transform(
        \DOMDocument $structure,
        \phpDocumentor\Transformer\Transformation $transformation
    ) {
    }
}