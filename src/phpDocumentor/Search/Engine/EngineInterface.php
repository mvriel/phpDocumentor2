<?php
namespace phpDocumentor\Search\Engine;

use phpDocumentor\Search\Document;

interface EngineInterface
{
    public function findBy($criteria);
    public function persist(Document $document);
    public function remove(Document $document);
    public function flush();
}
