<?php
namespace phpDocumentor\Plugin\Search\Engine\Configuration;

class LunrJs
{
    protected $schema = array();

    /** @var string $path The path where the index file needs to be written. */
    protected $path;

    public function __construct(array $schema, $path)
    {
        $this->schema = $schema;
        $this->path   = $path;
    }

    /**
     * @return array
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}
