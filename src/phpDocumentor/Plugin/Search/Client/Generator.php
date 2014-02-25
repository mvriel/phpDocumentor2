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

    public function generate(AdapterInterface $engine)
    {
        $class_name_parts = explode('\\', get_class($engine));
        $engine_type = $class_name_parts[count($class_name_parts)-1];

        $this->twig->setLoader(new \Twig_Loader_String());
        return $this->twig->render(
            file_get_contents(__DIR__.'/Templates/'.$engine_type.'.twig.php'),
            array('configuration' => $engine->getConfiguration())
        );
    }
}
