<?php

namespace phpDocumentor\Ast;

class InterfaceNode extends AbstractDocBlockNode
{
    public $name;
    public $fqsen;
    public $extends = array();
    public $methods = array();

    public $line_number;
    public $filename;

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

    public function setFqsen($fqsen)
    {
        $this->fqsen = $fqsen;
    }

    public function getFqsen()
    {
        return $this->fqsen;
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
}

