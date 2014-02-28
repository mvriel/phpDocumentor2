<?php
namespace phpDocumentor\Plugin\Search;

/**
 * Represents a single Document entity in Search Engine results.
 */
class Document extends \ArrayObject
{
    /** @var string the unique identifier for this Search Document */
    protected $id = '';

    /**
     * Sets the unique identifier for this Document.
     *
     * @param string $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the unique identifier for this Document.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
