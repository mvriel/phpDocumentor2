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
use phpDocumentor\Plugin\Search\Adapter\ElasticSearch;
use phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch as ElasticSearchConfiguration;

/**
 * Tests the Document class for the ElasticSearch Engine.
 *
 * @covers \phpDocumentor\Plugin\Search\Adapter\ElasticSearch
 */
class ElasticSearchTest extends \PHPUnit_Framework_TestCase
{
    /** @var ElasticSearch */
    protected $fixture;

    protected function setUp()
    {
        $configuration = new ElasticSearchConfiguration(new \Guzzle\Http\Client(), 'http://localhost:9200');
        $this->fixture = new ElasticSearch($configuration);
    }

    /**
     * @covers \phpDocumentor\Plugin\Search\Adapter\ElasticSearch::persist
     */
    public function testPersist()
    {
        $this->assertAttributeEmpty('updates', $this->fixture);

        $this->fixture->persist(new Document());

        $this->assertAttributeCount(1, 'updates', $this->fixture);
    }

    /**
     * @covers \phpDocumentor\Plugin\Search\Adapter\ElasticSearch::remove
     */
    public function testRemove()
    {
        $this->assertAttributeEmpty('removals', $this->fixture);

        $this->fixture->remove(new Document());

        $this->assertAttributeCount(1, 'removals', $this->fixture);
    }

    public function testFlush()
    {
        $document = new Document();
        $document->setId('1');
        $document['test'] = 'my_test';

        $this->fixture->persist($document);
        $this->fixture->flush();
    }
}