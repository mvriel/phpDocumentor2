<?php
namespace phpDocumentor\Plugin\Search\Adapter;

use phpDocumentor\Plugin\Search\Document;

class ElasticSearch implements AdapterInterface
{
    /** @var Document[] Documents that are ready to be purged from the Search Engine */
    protected $removals = array();

    /** @var Document[] Documents that are ready to be added or updated in the Search Engine */
    protected $updates  = array();

    /** @var Configuration\ElasticSearch The configuration specific for this Search Engine */
    protected $configuration;

    /**
     * Registers the configuration with this Search Engine.
     *
     * @param Configuration\ElasticSearch $configuration
     */
    public function __construct(Configuration\ElasticSearch $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function find($expression, $start = 0, $limit = 10)
    {
        $client = $this->getConfiguration()->getHttpClient();
        $result = $client->get($this->getBaseUrl() . '/_search?q=' . $expression);

        if (!isset($result->hits->hits)) {
            throw new \RuntimeException('An error occurred during the finding of documents');
        }

        $results = array();
        foreach ($result->hits->hits as $hit) {
            $document = new Document(json_decode($hit->_source, false));
            $document->setId($hit->_id);
            $results[] = $document;
        }

        return $results;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Document $document)
    {
        $this->updates[$document->getId()] = $document;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Document $document)
    {
        if (isset($this->updates[$document->getId()])) {
            unset($this->updates[$document->getId()]);
        }

        $this->removals[$document->getId()] = $document;
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * Assembles the Base Url (storage path) where the documents are stored in Elastic Search.
     *
     * @return string
     */
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

    /**
     * Converts the document contents, except id, to a representation that can be sent to ElasticSearch.
     *
     * @param Document $document
     *
     * @return string A JSON string containing all fields and their values of a Document.
     */
    protected function convert(Document $document)
    {
        return json_encode($document->getArrayCopy());
    }
}
