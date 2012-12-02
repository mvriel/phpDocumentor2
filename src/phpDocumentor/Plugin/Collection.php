<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2012 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
namespace phpDocumentor\Plugin;

/**
 * This class loads the plugins from the configuration and initializes them.
 */
class Collection extends \ArrayObject
{
    /**
     * Loads the plugins from the configuration.
     *
     * If no plugins are presented in the configuration then only the 'Core' plugin will be loaded.
     *
     * @param Factory           $factory
     * @param \SimpleXMLElement $configuration
     *
     * @return void
     */
    public function load(Factory $factory, $configuration)
    {
        // \Zend\Config\Config has a quirk; if there is only one entry then it
        // is not wrapped in an array, since we need that we re-wrap it
        // TODO: make configuration a flat PHP Array or well-formed object
        if (isset($configuration->path)) {
            $configuration = array($configuration);
        }

        // add the default plugin if none was set
        if (empty($configuration)) {
            $configuration = array('Core');
        }

        foreach ($configuration as $plugin_config) {
            $this[] = $factory->createFromFile(is_string($plugin_config) ? $plugin_config : $plugin_config->path);
        }
    }
}
