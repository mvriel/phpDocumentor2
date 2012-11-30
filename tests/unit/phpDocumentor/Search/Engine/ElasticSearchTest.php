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

namespace phpDocumentor\Search\Engine;

/**
 * Tests the Document class for the ElasticSearch Engine.
 *
 * @covers \phpDocumentor\Search\Engine\ElasticSearch
 */
class ElasticSearchTest extends \PHPUnit_Framework_TestCase
{
    /** @var ElasticSearch */
    protected $fixture;

    protected function setUp()
    {
        $configuration = new Configuration\ElasticSearch(new \Guzzle\Http\Client(), 'http://localhost:9200');
        $this->fixture = new ElasticSearch($configuration);
    }

    /**
     * @covers \phpDocumentor\Search\Engine\ElasticSearch::persist
     */
    public function testPersist()
    {
        $this->assertAttributeEmpty('updates', $this->fixture);

        $this->fixture->persist(new \phpDocumentor\Search\Document());

        $this->assertAttributeCount(1, 'updates', $this->fixture);
    }

    /**
     * @covers \phpDocumentor\Search\Engine\ElasticSearch::remove
     */
    public function testRemove()
    {
        $this->assertAttributeEmpty('removals', $this->fixture);

        $this->fixture->remove(new \phpDocumentor\Search\Document());

        $this->assertAttributeCount(1, 'removals', $this->fixture);
    }

    public function testFlush()
    {
        $document = new \phpDocumentor\Search\Document();
        $document->setId('1');
        $document['test'] = 'my_test';

        $this->fixture->persist($document);
        $this->fixture->flush();
    }

}