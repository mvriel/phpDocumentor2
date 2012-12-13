<?php

namespace phpDocumentor\Ast;

class PropertyNode extends AbstractDocBlockNode
{
    public $name;
    public $fqsen;

    public $type;
    public $default;

    public $line_number;

    public function setDefault($default)
    {
        $this->default = $default;
    }

    public function getDefault()
    {
        return $this->default;
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}