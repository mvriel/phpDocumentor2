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

namespace phpDocumentor\Transformer\Template\Reader;

use phpDocumentor\Transformer\Template;

/**
 * Test class for \phpDocumentor\Transformer\Template\Reader\BaseReader.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class BaseReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests whether the template is correctly set when instantiation a reader.
     *
     * @covers phpDocumentor\Transformer\Template\Reader\BaseReader::__construct
     * @covers phpDocumentor\Transformer\Template\Reader\BaseReader::getTemplate
     *
     * @return void
     */
    public function testInstantiation()
    {
        $template = $this->getMock('phpDocumentor\Transformer\Template');

        $reader = new BaseReaderMock($template);

        $this->assertSame($template, $reader->getTemplate());
    }

}

/**
 * Mock class for the BaseReaderTest.
 */
class BaseReaderMock extends BaseReader
{
    /**
     * Mock function to fulfill the abstract base class.
     *
     * @param string $body_text
     *
     * @return void
     */
    public function process($body_text)
    {
    }
}