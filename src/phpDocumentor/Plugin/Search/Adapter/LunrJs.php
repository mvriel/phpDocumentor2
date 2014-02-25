<?php
namespace phpDocumentor\Plugin\Search\Adapter;

use phpDocumentor\Plugin\Search\Document;

/**
 * Engine implementation for the lunr.js Javascript Full Text Search,
 *
 * LunrJs is capable of serializing data that is fed but this can only be done from within JavaScript.
 * As such this engine detects if node.js is installed and, if so, compiles an index. If node.js is not
 * installed then a dynamic index file is generated.
 *
 * The downside of the latter is that it costs a lot more bandwidth and performance, so for medium-sized
 * projects is recommended to install node or use any of the other Search Engines.
 * For large projects it is NOT recommended to use this Search Engine as it will cost too much performance.
 *
 * @link http://lunrjs.com
 */
class LunrJs implements AdapterInterface
{
    /** @var Configuration\LunrJs */
    protected $configuration;

    /** @var Document[] $updates */
    protected $updates = array();

    /**
     * Registers the configuration with this Search Engine.
     *
     * @param Configuration\LunrJs $configuration
     */
    public function __construct(Configuration\LunrJs $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritDoc}
     *
     * @return Configuration\LunrJs
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritDoc}
     *
     * @todo add support for querying the Lunr.js index using Node or Execjs.
     */
    public function find($expression, $start = 0, $limit = 10)
    {
        throw new \RuntimeException(
            'LunrJs is a pure Javascript Full Text Search Engine and can only be invoked from Javascript'
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
     */
    public function remove(Document $document)
    {
        // The LunrJs Search Engine does not support removing documents because the index is rebuilt from
        // scratch everytime
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $indexName = 'lunrIndex';

        // initialize the search engine and define the schema
        $index = 'var ' . $indexName . ' = lunr(function() {';
        $index .= 'this.ref("id");';
        foreach ($this->getConfiguration()->getSchema() as $field) {
            $index .= 'this.field("' . $field['name'] . '");';
        }
        $index .= '});';

        // populate the index
        foreach ($this->updates as $document) {
            $values = array('id' => $document->getId());
            foreach ($this->getConfiguration()->getSchema() as $field) {
                $values[$field['name']] = $document[$field['name']];
            }
            $index .= $indexName . '.add(' . json_encode($values) . ');';
        }

        file_put_contents($this->getIndexPath(), $index);
    }

    /**
     * Returns the location of the index file.
     *
     * @return string
     */
    protected function getIndexPath()
    {
        return $this->getConfiguration()->getPath() . DIRECTORY_SEPARATOR . 'index.lunr.js';
    }
}
