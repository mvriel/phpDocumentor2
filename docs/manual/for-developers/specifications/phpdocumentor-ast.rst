phpDocumentor Abstract Syntrax Tree (PDAST)
===========================================

Author(s):
    Mike van Riel <mike.vanriel@naenius.com>

Inspiration source (should be removed after finishing):
    http://www.w3.org/TR/SVG/struct.html

Introduction
------------

About a phpDocumentor Abstract Syntax Tree (PDAST)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This document intends to provide a concise description of the Abstract Syntax
Tree for the phpDocumentor application and introduce an Internet Media Type
specification that can be used for communication between phpDocumentor and
third parties.

PDAST is a language for describing the syntax structure, including in-source
documentation and meta-data, of a PHP Project for use in static analysis.

Mime Type and filename extension
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The Mime Type (or Internet Media Type) for this document is
*prs.phpdocumentor.ast+xml*, see RFC4288_ for more information regarding
the *prs* prefix. This Mime Type is not officially registered at IANA.

It is recommended that AST files for phpDocumentor have the extension
"`.pdast.xml`".

Compatibility with Other Standards Efforts
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

PDAST leverages and integrates with W3C specifications and standards efforts. By
leveraging and conforming to other standards, PDAST becomes more powerful and
makes it easier for users to learn how to interpret the data represented by
PDAST.

The following describes some of the ways in which PDAST maintains compatibility
with, leverages and integrates with other efforts:

* PDAST is an application of XML and is compatible with the Extensible Markup
  Language (XML) Recommendation [XML_]
* PDAST is compatible with the Namespaces in XML Recommendation [`XML-NS`_]
* PDAST is a representation format for the data described by the PHPDoc_
  Standard.

Conventions Used In This Document
---------------------------------

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in RFC2119_.

Definitions
-----------

In-source documentation
    In-source documentation refers to the description of syntactical elements of
    the PHP language, that are enclosed in DocComments, as recognized
    by the PHPDoc_ specification.

Comments
    Any comment_ as recognized by PHP. This category explicitly excludes
    DocComments and thus in-source documentation and is thus not featured in
    PDAST.

PHP Project
    A collection of PHP Source Files that when combined form a complete system
    such as an Application or Library.

Root directory
    The highest directory common to all files in the PHP Project. All 'Path'
    items mentioned in this document are relative to this directory. This is
    also not mentioned in the PDAST as the PDAST file must be able to be used
    independent of the machine where phpDocumentor was ran.

Document Structure
------------------

Defining a PHP Project: the 'project' element
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Container element

   Content model:
       Any number of the following elements, in any order:

       | 'namespace_'
       | 'file_'
       | 'index_'

   Attributes:
       | 'title'code-
       | 'version'

Attribute Definitions
#####################

`title`="<string_>"
    The title, or name, for this PHP Project

`version`="<Version_>"
    Indicates the PDAST language version to which this PHP Project conforms.

Overview
########

A PHP Project consists of a number of `namespace` elements and optionally a
number of `index` elements, contained within a `project` element.

A PHP Project may simply be an empty project, contain a single namespace and
file or have an extensive set of namespaces and files together with indices.

This element represents the container for a project and as such has all files
and indexes, which in turn contain all other syntactical elements.

Example
#######

The following example shows a simple PHP Project definition:

.. code-block:: xml

   <?xml version="1.0" encoding="utf-8"?>
   <project title="My Documentation" version="1.1">
       <namespace><name type="abbreviated">My</name></namespace>
       <file name="my/file.php" hash="ecbf63efefa9dda668e39eb3c99c46f6"></file>
       <index name="namespace"></index>
   </project>

.. _file:

Namespaces: The 'namespace' element
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
.. admonition:: Definition

   Categories:
       Container element

   Content model:
       | 'name_'
       | 'namespace_'
       | 'function_'
       | 'constant_'
       | 'interface_'
       | 'class_'
       | 'trait_'

   Attributes:
       None

Overview
########

A namespace is a syntactical container for other syntactical elements, even
other namespaces.

Example
#######

Files: The 'file' element
~~~~~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Container element, Structural element

   Content model:
       At most one of the following elements, in any order:

       | Any of the DocBlock_ elements
       | 'source_'

       Any number of the following elements, in any order:

       | 'namespace_alias_'
       | 'include_'
       | 'error_'
       | 'marker_'

   Attributes:
       | 'name'
       | 'hash'

Attribute Definitions
#####################

`name`="<Path_>"
    The full path for the given file relative to the PHP Project's root
    directory.

`hash`="<integer_>"
    A MD5 hash of the entire contents of the file. With this hash it is possible
    to accurately determine if a file has changed, any byte that has been
    changed will result in a different hash.
    phpDocumentor uses this internally to determine whether it needs to re-parse
    the file or not.

Overview
########

Example
#######

.. _index:

The 'index' element
~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Container element, Index element

   Content model:
       Any number of the following elements, in any order:

       | 'index_'

   Attributes:
       | 'name'
       | 'type'
       | 'count'
       | 'path'

Attribute Definitions
#####################

`name`="<string>"
    A string representing the name of this index item.

`type`="<string>"
    A string representing which type of elements are contained inside this index.
    An example can be `package`, where each index item represents a package.

`count`="<integer>"class
    If this index item represents a collection of other items then this
    attribute may be present indicating how many sub-items are present in the
    PDAST.
    An example of this may be an index of type `marker`, where the 'count'
    attribute represents how many markers with the same name are present in
    the source code.

`path`="<string>"


Overview
########

Example
#######

.. _source:

The 'source' element
~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element

   Content model:
       Character data, base-64 encoded gcompressed data of source code.

   Attributes:
       None

Attribute Definitions
#####################

Overview
########

Example
#######

.. _DocBlock:

The 'class' element
~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Container element, Structural element

   Content model:
       At most one of the following elements, in any order:

       | Any of the DocBlock_ elements

       Any number of the following elements, in any order:

       | 'name_'
       | 'extends_'
       | 'implements_'
       | 'uses_'
       | 'property_'
       | 'method_'
       | 'constant_'

   Attributes:
       | 'final'
       | 'abstract'
       | 'line_number'
       | 'package'
       | 'namespace'

Attribute Definitions
#####################

Overview
########

Example
#######

.. _interface:

The 'interface' element
~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Container element, Structural element

   Content model:
       At most one of the following elements, in any order:

       | Any of the DocBlock_ elements

       Any number of the following elements, in any order:

       | 'name_'
       | 'extends_'
       | 'implements_'
       | 'uses_'
       | 'method_'

   Attributes:
       | 'line_number'
       | 'package'
       | 'namespace'

Attribute Definitions
#####################

Overview
########

Example
#######

.. _property:

The 'property' element
~~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element, Structural element, Inheritable element

   Content model:
       At most one of the following elements, in any order:

       | Any of the DocBlock_ elements

       Any number of the following elements, in any order:

       | 'name_'
       | 'default_'

   Attributes:
       | 'final'
       | 'static'
       | 'visibility'
       | 'line_number'

Attribute Definitions
#####################

Overview
########

Example
#######

.. _method:

The 'method' element
~~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element, Structural element, Inheritable element

   Content model:
       At most one of the following elements, in any order:

       | Any of the DocBlock_ elements

       Any number of the following elements, in any order:

       | 'name_'
       | 'argument_'

   Attributes:
       | 'final'
       | 'abstract'
       | 'static'
       | 'visibility'
       | 'line_number'

Attribute Definitions
#####################

Overview
########

Example
#######

DocBlock elements
-----------------

Every element in the category Structural Element may be preceded by a DocBlock
in the source code and may thus have any of the elements mentiones in this
chapter.

.. _description:

The 'description' element
~~~~~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Container element

   Content model:
       Any number of the following elements, in any order:

       | 'content_'
       | 'tag_'

   Attributes:
       | 'type'

Attribute Definitions
#####################

`type`=<'short'|'long'>
    Defines the type of description, see PHPDoc_ for an elaborate description
    of both.

Overview
########

Example
#######

.. _tag:

The 'tag' element
~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element, Tag element

   Content model:
       Any number of the following elements, in any order:

       | 'type_'
       | 'description_'
       | 'attribute_'

   Attributes:
       | 'name'
       | 'line_number'

Attribute Definitions
#####################

Overview
########

Example
#######

.. _param:

The 'param' element
~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element, Tag element

   Content model:
       Any number of the following elements, in any order:

       | Any contained in the 'tag_' element
       | 'type_'
       | 'variable_'

   Attributes:
       | Any contained in the 'tag_' element

Overview
########

Example
#######

.. _param:

The 'inherited_from' element
~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element, Tag element

   Content model:
       Any number of the following elements, in any order:

       | Any contained in the 'tag_' element

   Attributes:
       | Any contained in the 'tag_' element
       | 'class'

Attribute Definitions
#####################

`class`="<FQCN_>"

Overview
########

The 'inherited_from' element is added on an element in the category 'Inheritable
element' if it is not explicitly defined inside the current container but
instead is imported from a superclass using inheritance.

Contrary to other tag elements this element is added by phpDocumentor and not
present in the source code as a 'tag'.

Example
#######

.. code-block:: xml

<?xml version="1.0" encoding="utf-8"?>

    <project ...>
        <namespace ...>
            <class ...>
                <method>
                    <name type="full">myMethod</name>
                    <inherited_from class="\SuperClass">
                </method>
            </class>
        </namespace>
    </project>

Common elements
---------------

.. _name:

The 'name' element
~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element

   Content model:
       Any character data.

   Attributes:
       | 'type'

Attribute Definitions
#####################

`type`="<abbreviated|full>"
    Determines what type of name is represented by this element. This attribute
    may be omitted, in which case the value 'full' is implied.

Overview
########

Example
#######

.. _default:

The 'default' element
~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element

   Content model:
       Any character data.

   Attributes:
       None

Overview
########

Example
#######

.. _content:

The 'content' element
~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element

   Content model:
       Any character data.

   Attributes:
       None

Overview
########

Example
#######

.. _attribute:

The 'attribute' element
~~~~~~~~~~~~~~~~~~~~~

.. admonition:: Definition

   Categories:
       Descriptive element

   Content model:
       Any character data.

   Attributes:
       | key

Overview
########

Example
#######

Types
-----

.. _FQCN:

FQCN

.. _integer:

integer

.. _Path:

Path

.. _string:

string

.. _Version:

Version

Example
-------

.. code-block::xml

   <?xml version="1.0" encoding="utf-8"?>

   <project title="Example">
       <namespace>
           <name type="abbreviation">My</name>
           <name type="full">\My</name>
           <class final="false" abstract="false" file="my/example.php"
               line_number="10"
           >
               <name type="full">\My\Example</name>
               <name type="abbreviation">Example</name>
               <description type="short">
                   <[[CDATA[This is a short description.]]>
               </description>
               <description type="long">
                   <[[CDATA[
                       This is a long description that
                       may span multiple lines.
                   ]]>
               </description>
               <author name="author" line_number="10">
                   <name>Mike van Riel</name>
                   <email>mike.vanriel@naenius.com</email>
               </author>
               <constant>
                   <name type="full">MY_CONSTANT</name>
                   <type>string</type>
               </constant>
               <property final="false" static="false" visibility="public"
                   line_number="10"
               >
                   <name type="full">my_property</name>
                   <description type="short">
                       <[[DATA[Example property]]>
                   </description>
                   <type>string</type>
               </property>
               <method final="false" abstract="false" static="false"
                   visibility="public" line_number="10"
               >
                   <name type="full">myMethod</name>
                   <description type="short">
                       <[[DATA[Example Method]]>
                   </description>
                   <argument by_reference="false">
                       <name type="full">argument1</name>
                       <description type="long">
                           <[[CDATA[Argument1's long description]]>
                       </description
                       <type>\SimpleXMLElement</type>
                       <type>null</type>
                       <default>null</default>
                   </argument>
                   <returns>string</returns>
                   <returns>null</returns>
               </method>
           </class>
       </namespace>
       <file name="my/example.php" hash="876434C4847393474942234"></file>
   </project>

.. _`XML-NS`: http://www.w3.org/TR/xml-names/
.. _XML:    http://www.w3.org/TR/xml/
.. _comment:  http://php.net/manual/en/language.basic-syntax.comments.php
.. _PHPDoc:   https://github.com/phpDocumentor/phpDocumentor2/blob/develop/docs/PSR.md
.. _RFC2119:  http://tools.ietf.org/html/rfc2119
.. _RFC4288:  http://tools.ietf.org/html/rfc4288#section-3.3
