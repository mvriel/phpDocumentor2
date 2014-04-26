<?php
namespace phpDocumentor\Plugin\Search\Adapter;

use phpDocumentor\Plugin\Search\Document;

/**
 * A no-nonsense pure-js implementation of a simple Search Engine.
 *
 * @author Timothée LHUILLIER<tlhuillier@global-distri.eu> The original Javascript for this implementation was written
 *     and contributed by Timothée LHUILLIER.
 */
class SimpleJs implements AdapterInterface
{
    const INDEX_FILENAME = 'index.simplesearch.js';

    /** @var Configuration\SimpleJs */
    protected $configuration;

    /** @var Document[] $updates */
    protected $updates = array();

    /**
     * Registers the configuration with this Search Engine.
     *
     * @param Configuration\SimpleJs $configuration
     */
    public function __construct(Configuration\SimpleJs $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritDoc}
     *
     * @return Configuration\SimpleJs
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
        throw new \RuntimeException(
            'SimpleJs is a pure Javascript Full Text Search Engine and can only be invoked from Javascript'
        );
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
     *
     * @codeCoverageIgnore because this method does nothing
     */
    public function remove(Document $document)
    {
        // The SimpleJs Search Engine does not support removing documents because the index is rebuilt from
        // scratch everytime
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $indexElements = array();
        foreach ($this->updates as $document) {
            $indexElements[] = $this->convert($document);
        }
        file_put_contents($this->getIndexPath(), 'var listElements = [' . implode(',', $indexElements) . '];');
    }

    /**
     * Returns the location of the index file.
     *
     * @return string
     */
    protected function getIndexPath()
    {
        return $this->getConfiguration()->getPath() . DIRECTORY_SEPARATOR . self::INDEX_FILENAME;
    }

    /**
     * Converts the document contents, including id, to a Javascript command that adds it to the index.
     *
     * @param Document $document
     *
     * @return string A javascript command adding the document to the index.
     */
    protected function convert($document)
    {
        $values = array(
            'id' => $document->getId(),
            'type' => $document['type'],
            'name' => $document['fqsen'],
        );

        return json_encode($values);
    }
}
