<?php
namespace phpDocumentor\Plugin\Search\Adapter\Configuration;

/**
 * Configuration class for the LunrJs Search Engine.
 */
class LunrJs implements ConfigurationInterface
{
    /**
     * The schema definition for the LunrJs engine.
     *
     * Each entry in the schema should consist of the following fields:
     *
     * * name, the name of the field
     *
     * More options might be added later based on the availability in LunrJs.
     *
     * @var string[]
     */
    protected $schema = array();

    /** @var string $path The path where the index file needs to be written. */
    protected $path;

    /**
     * Initializes the configuration with the schema and path.
     *
     * @param string[] $schema
     * @param string $path
     *
     * @see self::$schema for more information on the format of the $schema array.
     */
    public function __construct(array $schema, $path)
    {
        $this->schema = $schema;
        $this->path   = $path;
    }

    /**
     * Returns the schema definition for this instance.
     *
     * @return string[]
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Returns the path where the LunrJs index should be built to.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
