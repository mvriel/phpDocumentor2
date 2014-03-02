<?php
namespace phpDocumentor\Plugin\Search\Adapter\Configuration;

use Guzzle\Http\Client;

/**
 * Class ElasticSearch
 * @package phpDocumentor\Plugin\Search\Adapter\Configuration
 */
class ElasticSearch implements ConfigurationInterface
{
    /** @var Client */
    protected $httpClient;

    /** @var string */
    protected $uri;

    /** @var string */
    protected $index = 'documentation';

    /** @var string */
    protected $type  = 'api';

    /**
     * Initializes the configuration with the provided Guzzle Client and ElasticSearch location uri.
     *
     * @param Client $httpClient
     * @param string $uri
     */
    public function __construct(Client $httpClient, $uri = 'http://localhost:9200')
    {
        $this->httpClient = $httpClient;
        $this->uri        = $uri;
    }

    /**
     * Sets the name of the index in ElasticSearch.
     *
     * @param string $index
     *
     * @return void
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Returns the name of the index in ElasticSearch.
     *
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Sets the type for the index in ElasticSearch.
     *
     * @param string $type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the type for the index in ElasticSearch.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the location for the ElasticSearch Server.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns the Guzzle Client with which to connect to the ElasticSearch server.
     *
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
