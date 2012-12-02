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

use phpDocumentor\Event\Dispatcher;

/**
 * Factory class for instantiating new plugins using alternate datasources.
 */
class Factory
{
    /** @var Dispatcher */
    protected $event_dispatcher = null;

    /** @var \SimpleXMLElement */
    protected $configuration = null;

    /**
     * Registers the Event Dispatcher and Confguration onto the Factory.
     *
     * @param Dispatcher        $event_dispatcher Event dispatcher where events are be dispatched to.
     * @param \SimpleXMLElement $configuration    Configuration file which can be used to load parameters into plugins.
     */
    public function __construct($event_dispatcher, $configuration)
    {
        $this->event_dispatcher = $event_dispatcher;
        $this->configuration    = $configuration;
        $this->translator       = new \Zend\I18n\Translator\Translator();

        $this->translator->setLocale('en')->setFallbackLocale('en');
    }

    /**
     * Creates a new Plugin object with the given configuration path.
     *
     * @param string $path Directory to the plugin.xml.
     *
     * @return Plugin
     */
    public function createFromFile($path)
    {
        $plugin = new Plugin($this->event_dispatcher, $this->configuration, $this->translator);
        $plugin->load($path);
        return $plugin;
    }
}
