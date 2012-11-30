Mapping
=======

Search Engines work by indexing documents, containing key/value pairs of information, and link them to a destination
URL. phpDocumentor's Search writer should provide an easy way to map the information in the Abstract Syntax Tree (AST)
to the format desired by the user.

It should be possible to define a mapping for all types of documents or even override this default with a specific
mapping for an individual document type. Each document type matches a type of structural element; as such the following
Document Types are acknowledged by phpDocumentor:

* namespace
* class
* interface
* trait
* function
* method
* constant
* property

.. note::

   Packages are explicitly not available as they are an artificial construct and will take more time to create; if user
   requests indicate that there is a desire for this than that will be reconsidered.

The mappings can be defined in with the Search writer by providing a list of mappings with a key (as to be used in the
document) and a template string that is used to transform the Structural Element's AST representation into what needs to
be inserted in the search engine.

Example:

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <template>
    ...
        <transformation writer="Search">