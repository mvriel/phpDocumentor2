<?php

namespace phpDocumentor\Ast;

class ProjectNode
{
    public $name;
    public $namespaces = array();
    public $files = array();
    public $indexes = array();

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setIndexes($indexes)
    {
        $this->indexes = $indexes;
    }

    public function getIndexes()
    {
        return $this->indexes;
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
}