<?php

namespace phpDocumentor\Plugin\Search;

use phpDocumentor\Plugin\Search\Adapter\AdapterInterface;

/**
 * Search engine manager class that can find and persist documents to the given Search Engine by adapter.
 */
class EngineManager implements AdapterInterface
{
    /** @var AdapterInterface */
    protected $adapter;

    /**
     * Creates a new Engine that talks to the given adapter.
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration()
    {
        return $this->adapter->getConfiguration();
    }

    /**
     * {@inheritDoc}
     */
    public function find($expression, $start = 0, $limit = 10)
    {
        return $this->adapter->find($expression, $start, $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Document $document)
    {
        $this->adapter->persist($document);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Document $document)
    {
        $this->adapter->remove($document);
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $this->adapter->flush();
    }
}