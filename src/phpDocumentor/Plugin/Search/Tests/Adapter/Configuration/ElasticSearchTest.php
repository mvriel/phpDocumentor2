<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2014 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Plugin\Search\Tests\Adapter\Configuration;

use Guzzle\Http\Client;
use Mockery as m;
use phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch;

/**
 * Contains tests for the phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch class.
 */
class ElasticSearchTest extends \PHPUnit_Framework_TestCase
{
    /** @var m\MockInterface|Client */
    protected $clientMock;

    /** @var string */
    protected $uri;

    /** @var ElasticSearch */
    private $fixture;

    /**
     * Sets up the fixture including dependencies.
     */
    protected function setUp()
    {
        $this->clientMock = m::mock('Guzzle\Http\Client');
        $this->uri        = 'http://elasticsearch.example.org:9100';
        $this->fixture    = new ElasticSearch($this->clientMock, $this->uri);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::__construct
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::getHttpClient
     */
    public function testIfClientIsCorrectlySetUponInitialization()
    {
        $this->assertSame($this->clientMock, $this->fixture->getHttpClient());
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::__construct
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::getUri
     */
    public function testIfElasticSearchUriIsCorrectlySetUponInitialization()
    {
        $this->assertSame($this->uri, $this->fixture->getUri());
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::getIndex
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::setIndex
     */
    public function testIfIndexCanBeChanged()
    {
        $this->assertSame('documentation', $this->fixture->getIndex());

        $this->fixture->setIndex('newIndex');

        $this->assertSame('newIndex', $this->fixture->getIndex());
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::getType
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch::setType
     */
    public function testIfTypeCanBeChanged()
    {
        $this->assertSame('api', $this->fixture->getType());

        $this->fixture->setType('newType');

        $this->assertSame('newType', $this->fixture->getType());
    }
}
