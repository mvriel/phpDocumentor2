<?php

namespace phpDocumentor\Ast;

class MethodNode extends AbstractDocBlockNode
{
    public $name;
    public $fqsen;

    public $abstract = false;
    public $final = false;
    public $static = false;
    public $visibility;
    public $line_number;

    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    public function getAbstract()
    {
        return $this->abstract;
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

    public function setStatic($static)
    {
        $this->static = $static;
    }

    public function getStatic()
    {
        return $this->static;
    }

    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }
}

