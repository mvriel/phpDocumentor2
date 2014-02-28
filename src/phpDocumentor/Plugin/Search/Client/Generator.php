<?php
namespace phpDocumentor\Plugin\Search\Client;

use \phpDocumentor\Plugin\Search\Adapter\AdapterInterface;

/**
 * Generates the code that is consumed by a third-party in order to invoke a generator.
 *
 * This class is used by, for example, templates to receive a specific piece of javascript or PHP code
 * that can be used as a client and to interact with the associated engine.
 */
class Generator
{
    /** @var \Twig_Environment */
    protected $twig;

    /**
     * Registers the Twig environment which is used to render the templates with.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Renders the client code for an engine and returns it.
     *
     * This method allows the caller to generate a piece of client code that can be included, embedded or called in
     * another page. Because most engines have their interfacing split between frontend code (usually Javascript) and
     * a backend script that is invoked (usually PHP) this method can generate a specific type per engine.
     *
     * Specific engines may have types other than 'backend' and 'frontend', please review the documentation for that
     * engine that you want to use for more information.
     *
     * @param AdapterInterface $engine The engine for which to create a client.
     * @param string           $type   The type of client to receive, usually 'backend' or 'frontend'.
     *
     * @todo for next version: change twig handling from the string loader to the normal file loader so that we can
     *   pass alternate template locations to this object and thus allow plugins to add their own templates.
     *
     * @return string the client code or an empty string if the given type is not supported for this engine.
     */
    public function generate(AdapterInterface $engine, $type = 'backend')
    {
        return $this->generateClientFromTemplateWithConfiguration(
            $this->getTemplateFolder() . $this->getTemplateFilename($engine, $type),
            $engine->getConfiguration()
        );
    }

    /**
     * Returns a rendered client using the provided template and configuration.
     *
     * This method will pass the provided file's contents through Twig with the configuration provided as a global
     * variable called 'configuration'. The result of this rendering is returned.
     *
     * @param string $templatePath  The location of the template.
     * @param object $configuration
     *
     * @return string
     */
    protected function generateClientFromTemplateWithConfiguration($templatePath, $configuration)
    {
        if (! file_exists($templatePath)) {
            return '';
        }

        $this->twig->setLoader(new \Twig_Loader_String());

        return $this->twig->render(file_get_contents($templatePath), array('configuration' => $configuration));
    }

    /**
     * Returns the location of the client templates.
     *
     * @return string
     */
    protected function getTemplateFolder()
    {
        return __DIR__ . '/Templates/';
    }

    /**
     * Returns the filename for a template based on the provided engine and type.
     *
     * @param AdapterInterface $engine
     * @param string           $type
     *
     * @return string
     */
    protected function getTemplateFilename(AdapterInterface $engine, $type)
    {
        return strtolower($this->findEngineType($engine) . '.' . $type . '.twig');
    }

    /**
     * Attempts to discern the type of Engine based on its class name.
     *
     * @param AdapterInterface $engine
     *
     * @return string
     */
    protected function findEngineType(AdapterInterface $engine)
    {
        $class_name_parts = explode('\\', get_class($engine));
        $engine_type = $class_name_parts[count($class_name_parts) - 1];

        return $engine_type;
    }
}
