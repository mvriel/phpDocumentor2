Introduction
============

This document describes how search should work for phpDocumentor and sibling projects.

Background
----------

During the early development of phpDocumentor has the search option been removed because large projects, such as Zend
Framework, had large performance issues due to the size of the JSON index file.

Search, however is an often requested feature and should not be neglected to be implemented. However a new approach
needs to be made with regards to the way search works.

In addition; with our new sibling project 'Scrybe' has search become even more important as it is the primary way to
search for documents.

Goals
-----

The following goals have been defined as being primary targets to attain:

1. Being able to search a project the size of Zend Framework 2 using phpDocumentor2.
2. This must be designed as a re-usable module for other projects, as Scrybe.
3. It must be possible to switch the backend from a simple plain-text index to, for example, Zend_Lucene, Solr or
   ElasticSearch.
4. A plain-text backend needs to be designed where the searching itself is done completely in Javascript; the generation
   of indexes may be in PHP.

   1. Downloading the index in chunks of max 50kb to 100kb (mobile support)
   2. High performance, the time to process a Javascript chunk may not be more than 1 second, preferable 0.5s or lower.

5. High performance, the time to page load may not be increased with more than 0.3 seconds.

The following secondary goals have been set:

1. Support searching for partial contents on specific fields (such as FQSEN)
2. Support linking multiple systems together (such as the search of phpDocumentor and Scrybe).

