Theory of Operation
===================

Search is all about attempting to find a Document using an Expression provided by the Consumer. But the Search Engine
can only return meaningful results if it is first fed with a series of Documents and its fields containing the data to
search on.

This component provides an abstraction with which it is possible to populate and consume different Search Engines
independent of the application architecture.

A typical usage scenario is that the application provides the Search Engine with a series of Documents, including data,
and that the Consumer is able to query that Search Engine using the Frontend.

.. code-block::

   <?php
   $document = new Document($id, array(
       'field1' => 'value'
   ));

   $search_engine = new \phpDocumentor\Plugin\Search\EngineManager(new Search\Adapter\ElasticSearch());
   $search_engine->persist($document);
   $search_engine->flush();

   $results = $search_engine->query($expression);

.. note:: The API design and DSL is inspired by the Doctrine Project for recognizability and because concepts overlap.
