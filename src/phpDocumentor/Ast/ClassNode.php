<?php

namespace phpDocumentor\Ast;

class ClassNode extends AbstractDocBlockNode
{
    public $name;
    public $fqsen;
    public $extends;
    public $implements = array();

    public $abstract = false;
    public $final = false;

    public $constants = array();
    public $properties = array();
    public $methods = array();

    public $line_number;
    public $filename;

    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    public function getAbstract()
    {
        return $this->abstract;
    }

    public function setConstants($constants)
    {
        $this->constants = $constants;
    }

    public function getConstants()
    {
        return $this->constants;
    }

    public function setExtends($extends)
    {
        $this->extends = $extends;
    }

    public function getExtends()
    {
        return $this->extends;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFinal($final)
    {
        $this->final = $final;
    }

    public function getFinal()
    {
        return $this->final;
    }

    public function setFqsen($fqsen)
    {
        $this->fqsen = $fqsen;
    }

    public function getFqsen()
    {
        return $this->fqsen;
    }

    public function setImplements($implements)
    {
        $this->implements = $implements;
    }

    public function getImplements()
    {
        return $this->implements;
    }

    public function setLineNumber($line_number)
    {
        $this->line_number = $line_number;
    }

    public function getLineNumber()
    {
        return $this->line_number;
    }

    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    public function getProperties()
    {
        return $this->properties;
    }
}

