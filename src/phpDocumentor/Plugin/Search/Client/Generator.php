<?php
namespace phpDocumentor\Search\Client;

use \phpDocumentor\Plugin\Search\Adapter\AdapterInterface;

class Generator
{
    /** @var \Twig_Environment */
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generate(AdapterInterface $engine, $type = 'backend')
    {
        $filename = $this->getTemplateFolder() . $this->findEngineType($engine) . '.' . $type . '.twig.php';
        if (! file_exists($filename)) {
            return '';
        }

        return $this->generateClientFromTemplateWithConfiguration($filename, $engine->getConfiguration());
    }

    /**
     * @param $filename
     * @param $configuration
     * @return string
     */
    protected function generateClientFromTemplateWithConfiguration($filename, $configuration)
    {
        $parameters = array('configuration' => $configuration);
        $this->twig->setLoader(new \Twig_Loader_String());

        return $this->twig->render(file_get_contents($filename), $parameters);
    }

    /**
     * @param AdapterInterface $engine
     * @return mixed
     */
    protected function findEngineType(AdapterInterface $engine)
    {
        $class_name_parts = explode('\\', get_class($engine));
        $engine_type = $class_name_parts[count($class_name_parts) - 1];
        return $engine_type;
    }

    /**
     * @return string
     */
    protected function getTemplateFolder()
    {
        return __DIR__ . '/Templates/';
    }
}
