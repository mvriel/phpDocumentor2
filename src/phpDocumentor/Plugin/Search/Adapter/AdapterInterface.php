<?php
namespace phpDocumentor\Plugin\Search\Adapter;

use phpDocumentor\Plugin\Search\Document;

interface AdapterInterface
{
    /**
     * Returns the adapter-specific configuration object.
     *
     * @return object
     */
    public function getConfiguration();

    /**
     * Evaluates the given expression and tries to find, and return, a number of Documents equal to the amount found or
     * the limit if provided.
     *
     * @param string $expression A query expression used to limit the type of documents returned.
     * @param int    $start      At which point in the result set to start returning documents.
     * @param int    $limit      How many documents to return at maximum.
     *
     * @return Document[]
     */
    public function find($expression, $start = 0, $limit = 10);

    /**
     * Marks the given document for addition in the Search Engine so it can be found later again.
     *
     * Please note that the actual writing to the Search Engine happens once the {@see flush()} method is called. Until
     * then all addition and removal operations are stored in memory and unless flushed not found in the Search Engine.
     *
     * @param Document $document
     *
     * @return void
     */
    public function persist(Document $document);

    /**
     * Marks a document for removal so it will be removed once the flush method is called.
     *
     * Please note that the actual writing to the Search Engine happens once the {@see flush()} method is called. Until
     * then all addition and removal operations are stored in memory and unless flushed not found in the Search Engine.
     *
     * @param Document $document
     *
     * @return void
     */
    public function remove(Document $document);

    /**
     * Writes all changes to the Search Engine's data to the Search Engine.
     *
     * @return void
     */
    public function flush();
}
