<?php
namespace phpDocumentor\Ast;

class ArgumentNode
{
    public $by_reference = false;
    public $name;
    public $description;
    public $type;
    public $default;

    public function setByReference($by_reference)
    {
        $this->by_reference = $by_reference;
    }

    public function getByReference()
    {
        return $this->by_reference;
    }

    public function setDefault($default)
    {
        $this->default = $default;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
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
