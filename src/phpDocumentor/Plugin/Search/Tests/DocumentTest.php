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

namespace phpDocumentor\Plugin\Search\Tests;

use phpDocumentor\Plugin\Search\Document;

/**
 * Tests the Document class for the Search Engine.
 *
 * @covers \phpDocumentor\Plugin\Search\Document
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \phpDocumentor\Plugin\Search\Document::getId
     * @covers \phpDocumentor\Plugin\Search\Document::setId
     */
    public function testId()
    {
        $document = new Document();

        $this->assertEmpty($document->getId());

        $document->setId(1);
        $this->assertEquals(1, $document->getId());
    }
}
