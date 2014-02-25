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
    const INDEX_FILENAME = 'index.lunr.js';

    /** @var string the name of the index */
    protected $indexName = 'lunrIndex';

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
     *
     * @codeCoverageIgnore because this method does nothing
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
        $index = $this->generateEngineInstantiation();

        foreach ($this->updates as $document) {
            $index .= $this->convert($document);
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
        return $this->getConfiguration()->getPath() . DIRECTORY_SEPARATOR . self::INDEX_FILENAME;
    }

    /**
     * Initialize the search engine and define the schema.
     *
     * @return string
     */
    protected function generateEngineInstantiation()
    {
        $index = 'var ' . $this->indexName . ' = lunr(function() {';
        $index .= 'this.ref("id");';
        $index .= $this->generateSchema();
        $index .= '});';

        return $index;
    }

    /**
     * @return string
     */
    protected function generateSchema()
    {
        $schema = '';
        foreach ($this->getConfiguration()->getSchema() as $field) {
            $schema .= 'this.field("' . $field['name'] . '");';
        }
        return $schema;
    }

    /**
     * Converts the document contents, including id, to a Javascript command that adds it to the Lunr index.
     *
     * @param Document $document
     *
     * @return string A javascript command adding the document to the index.
     */
    protected function convert($document)
    {
        $values = array('id' => $document->getId());
        foreach ($this->getConfiguration()->getSchema() as $field) {
            $values[$field['name']] = $document[$field['name']];
        }

        return $this->indexName . '.add(' . json_encode($values) . ');';
    }
}
