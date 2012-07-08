@example
========

The @example tags is used to embed examples (inline and external) with
:term:`Structural Elements` and show them syntax-highlighted in the
documentation.

Syntax
------

    @example <path>
    @example <example code>

Description
-----------

phpDocumentor supports 2 ways to include example documentation:

* Using a path to an external file
* Providing inline example code

Distinguishing between the two supported formats
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

phpDocumentor assumes that the contents of the @example tag is inline example
code unless it matches one of the following rules:

* Starts with a scheme, thus file://, http://, etc
* Resembles a path, if the part after the example tag matches the following rules

  * MAY start with [A-Za-z]:\ or / (absolute file)
  * MAY contain 0..n directories, which is any character except /, \, 0x00, $,
    %, [, ],  (, ), {. }. < and > followed by a / or \
  * MUST contain a filename, which is any character except /, \, 0x00, $, %, [,
    ],  (, ), {. }. < and >
  * MUST be followed by an extension, which is a dot (.), followed by 3 to 5
    alphanumeric characters (A-Za-Z0-9)

  .. note: Directory and filenames may not start with a hyphen

  Examples:

      C:\My\Example\File.php
      \My\Example\File.php
      C:\File.php
      \File.php
      My\Example\File.php
      My/Example/File.php

Using a path
~~~~~~~~~~~~

In its first form will phpDocumentor attempt to read the file from the specified
path and will accept any file that `fopen<http://www.php.net/fopen>` will accept.
phpDocumentor checks the path to ensure that the file retrieved has a
valid .php extension as defined in phpDocumentor's configuration (defaults to
php, php3 and phtml).

If given an absolute path, phpDocumentor will use that file or output an error
if the file does not exist.

If given a relative path (no leading [A-Z]:\ or /) phpDocumentor will try to
locate the example file using the following rules:

1. Searches for examples files first in the directory specified by the -e
   (or --examplesdir) option, if present.
2. It will next search for the file in an examples/ subdirectory of the current
   file's directory.
3. Last it will search for a subdirectory named "examples" in the
   :term:`Project Root`.

Using inline example code
~~~~~~~~~~~~~~~~~~~~~~~~~

In its second form it will take the given example and show that in the generated
documentation.

Examples
--------

.. code-block:: php
   :linenos:

    /**
     * My function
     *
     * @example /path/to/example.php
     * @example anotherexample.php
     * @example echo strlen('6')
     * @example
     *   $a = 1+1;
     *   $a *= 2;
     *   echo $a;
     */
    function testExample()
    {
    }