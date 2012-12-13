<?php

namespace phpDocumentor\Ast;

class NamespaceNode
{
    public $name;
    public $fqsen;

    public $namespaces = array();

    public $classes = array();
    public $interfaces = array();
    public $traits = array();

    public function setClasses($classes)
    {
        $this->classes = $classes;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function setFqsen($fqsen)
    {
        $this->fqsen = $fqsen;
    }

    public function getFqsen()
    {
        return $this->fqsen;
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

    public function setNamespaces($namespaces)
    {
        $this->namespaces = $namespaces;
    }

    public function getNamespaces()
    {
        return $this->namespaces;
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

