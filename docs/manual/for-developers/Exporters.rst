Exporters
=========

Introduction
------------

An Exporter is part of the parsing phase of the project and exports the data
that is collected in the Reflection process to an Abstract Syntax Tree Document
(often abbreviated to 'the AST').

Theory of Operation
-------------------

An Exporter is an object, that is instantiated with the
``\phpDocumentor\Parser\Parser`` object, which can read a reflected file and
convert the contained data into an abstract representation. This representation
can then be consumed by the Transformer, or by a third-party application that
wants to interpret the data.

    An example is the 'Xml' Exporter, which is capable of transforming the
    reflected information into a single XML file (i.e. ``structure.xml``), which
    the XSL writer in the transformation process uses as source.

Configuring which exporter to use
---------------------------------

Creating your own Exporter
--------------------------