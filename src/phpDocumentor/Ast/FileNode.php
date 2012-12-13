<?php

namespace phpDocumentor\Ast;

class FileNode extends AbstractDocBlockNode
{
    public $name;
    public $path;
    public $hash;
    public $source = null;

    public $namespace_aliases = array();
    public $includes = array();

    public $constants = array();
    public $functions = array();
    public $classes = array();
    public $interfaces = array();
    public $traits = array();

    public $errors = array();

    public function setClasses($classes)
    {
        $this->classes = $classes;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function setConstants($constants)
    {
        $this->constants = $constants;
    }

    public function getConstants()
    {
        return $this->constants;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setFunctions($functions)
    {
        $this->functions = $functions;
    }

    public function getFunctions()
    {
        return $this->functions;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setIncludes($includes)
    {
        $this->includes = $includes;
    }

    public function getIncludes()
    {
        return $this->includes;
    }

    public function setInterfaces($interfaces)
    {
        $this->interfaces = $interfaces;
    }

    public function getInterfaces()
    {
        return $this->interfaces;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setNamespaceAliases($namespace_aliases)
    {
        $this->namespace_aliases = $namespace_aliases;
    }

    public function getNamespaceAliases()
    {
        return $this->namespace_aliases;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setTraits($traits)
    {
        $this->traits = $traits;
    }

    public function getTraits()
    {
        return $this->traits;
    }
}