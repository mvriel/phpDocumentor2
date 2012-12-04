<?php
namespace phpDocumentor\Search\Engine;

use phpDocumentor\Search\Document;

interface EngineInterface
{
    public function getConfiguration();
    public function find($expression, $start = 0, $limit = 10);
    public function persist(Document $document);
    public function remove(Document $document);
    public function flush();
}
