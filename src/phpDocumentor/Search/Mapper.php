<?php

namespace phpDocumentor\Search;

/**
 * The mapper is used to convert an object to a Document for indexing.
 *
 * This is accomplished by providing a definition array and an object used to populate the fields.
 * All definitions are parsed using Twig so it is possible to create complex field contents using the provided object.
 *
 * Example:
 *
 *     $definition = array(
 *         'id'   => 'data.id',
 *         'name' => 'data.name'
 *     );
 *     $mapper = new Mapper\Json($definition, $twig);
 *
 *     // converts the given object to a JSON Document
 *     $json = $mapper->convert($object);
 *
 * @see $definition for an explanation of the definition array.
 */
class Mapper
{
    /**
     * Representation of a document with the generating rules.
     *
     * Each element in this array represents a single field (key) in the document with a Twig template as
     * definition (value).
     *
     * Example:
     *
     *     $definition = array(
     *         'id'   => 'data.id',
     *         'name' => 'data.name'
     *     );
     *
     * @var string[]
     */
    protected $definition = array();

    /** @var \Twig_Environment */
    protected $twig_environment;

    /**
     * @param array $definition
     */
    public function __construct(array $definition)
    {
        $this->setDefinition($definition);
    }

    /**
     *
     *
     * @param object $object
     *
     * @return mixed
     */
    public function populate(Document $document, $object)
    {
        foreach ($this->definition as $field => $template) {
            $value = $this->getTwigEnvironment()->render($template, array('data' => $object));
            if ($field === 'id') {
                $document->setId($value);
            } else {
                $document[$field] = $value;
            }
        }

        return $document;
    }

    protected function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    protected function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param \Twig_Environment $twig_environment
     */
    public function setTwigEnvironment($twig_environment)
    {
        $this->twig_environment = $twig_environment;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwigEnvironment()
    {
        if (!$this->twig_environment) {
            $this->setTwigEnvironment(new \Twig_Environment(new \Twig_Loader_String()));
        }

        return $this->twig_environment;
    }
}
