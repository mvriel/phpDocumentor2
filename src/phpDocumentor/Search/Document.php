<?php
namespace phpDocumentor\Search;

class Document extends \ArrayObject
{
    protected $id  = '';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
