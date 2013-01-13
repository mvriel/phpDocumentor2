Defining URLs
=============

Introduction
------------

Several items of functionality in phpDocumentor depend on your url scheme. Examples are the tags @see, @uses, @param
and more. All these items try to link to other parts of the documentation to help the user to navigate easily through
the application.

To be able to generate these links phpDocumentor needs to know how to translate a :term:`Fully Qualified Structural Element
Name (FQSEN)` to a URL within your chosen template.

    Example: phpDocumentor needs to know that the method :term:`FQSEN` ``\phpDocumentor\Application::run()`` points to the URL
    ``/classes/phpDocumentor.Application#method_run``.

phpDocumentor uses a special component named a **Router** to convert a :term:`FQSEN` to URL.

Routers
-------

A router is able to convert a :term:`FQSEN` to an URL by checking the syntax of the :term:`FQSEN` and calling a method
to construct a URL.

Routers recognize the following elements by their FQSEN:

=============== ===============================
Element type    FQSEN Notation
=============== ===============================
Namespaces      ``\My\Space``
Variables       ``\My\Space\$variable``
Functions       ``\My\Space\Function()``
Constants       ``\My\Space\CONSTANT``
Classes         ``\My\Space\Class``
Interfaces      ``\My\Space\Interface``
Traits          ``\My\Space\Trait``
Methods         ``\My\Space\Class::myMethod()``
Class Constants ``\My\Space\Class::myMethod()``
Properties      ``\My\Space\Class::$property``
=============== ===============================
