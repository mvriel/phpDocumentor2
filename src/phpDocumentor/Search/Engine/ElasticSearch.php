<?php
namespace phpDocumentor\Search\Engine;

use phpDocumentor\Search\Document;
use Guzzle\Http\Client;

class ElasticSearch implements EngineInterface
{
    protected $removals = array();
    protected $updates  = array();

    /** @var Configuration\ElasticSearch */
    protected $configuration;

    public function __construct(Configuration\ElasticSearch $configuration)
    {
        $this->setConfiguration($configuration);
    }

    public function find($expression, $start = 0, $limit = 10)
    {
        $client = $this->getConfiguration()->getHttpClient();
        $result = $client->get($this->getBaseUrl() . '/_search?q=' . $expression);

        $results = array();
        foreach ($result->hits->hits as $hit) {
            $document = new Document(json_decode($hit->_source, false));
            $document->setId($hit->_id);
            $results[] = $document;
        }

        return $results;
    }

    public function persist(Document $document)
    {
        $this->updates[$document->getId()] = $document;
    }

    public function remove(Document $document)
    {
        if (isset($this->updates[$document->getId()])) {
            unset($this->updates[$document->getId()]);
        }

        $this->removals[$document->getId()] = $document;
    }

    public function flush()
    {
        $client = $this->getConfiguration()->getHttpClient();
        $base_url = $this->getBaseUrl();

        /** @var Document $document */
        foreach ($this->updates as $document) {
            try {
                $client
                    ->put(
                        $base_url . '/' . $document->getId(),
                        array('Content-Type: application/json'),
                        $this->convert($document)
                    )
                    ->send();
            } catch (\Exception $e) {
                throw new PersistException(
                    'An error occurred during the persisting of document '. $document->getId()
                    . ', the system reports: ' . $e->getMessage(),
                    0,
                    $e
                );
            }
        }

        foreach ($this->removals as $document) {
            $client->delete($base_url . '/' . $document->getId());
        }
    }

    protected function getBaseUrl()
    {
        return implode(
            '/',
            array(
                $this->getConfiguration()->getUri(),
                $this->getConfiguration()->getIndex(),
                $this->getConfiguration()->getType()
            )
        );
    }

    protected function convert(Document $document)
    {
        return json_encode($document->getArrayCopy());
    }

    /**
     * @param Configuration\ElasticSearch $configuration
     */
    public function setConfiguration(Configuration\ElasticSearch $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return \phpDocumentor\Search\Engine\Configuration\ElasticSearch
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
