<?php
namespace phpDocumentor\Plugin\Search\Adapter\Configuration;

/**
 * Configuration class for the SimpleJs Search Engine.
 */
class SimpleJs implements ConfigurationInterface
{
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the path where the index should be build.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
