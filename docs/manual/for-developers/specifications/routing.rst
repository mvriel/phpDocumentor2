Routing
=======

Introduction
------------

Each individual template is allowed to determine its own routing scheme by
changing the 'artifact' attribute of a transformation. This is a powerful
system that allows the template builder a lot of freedom, but comes at the cost
of complexity for phpDocumentor, when it comes to building URLs within the
application.

For example, the `@see` tag is capable of linking the tag to a URL the belongs
to a "Structural Element" in the final output. It is however the template that
determines where a specific "Structural Element" may be, it can be an
individual file or just an anchor inside a file.

To solve this issue we allow templates to choose a "Router" that is capable of
providing and generating a URL for a linking resource.

.. important::

   The template.xml specification should be expanded with a version number
   with which we can switch between default routers if desired.

Scope
-----

It is recommended that the reader of this document has knowledge of the
following subjects:

* What a Fully Qualified Structural Element Name (FQSEN) is
* The Abstract Syntax Tree of phpDocumentor
* The template.xml specification
* The Transformation process

Theory of Operation
-------------------

Routing may occur by transforming a FQSEN into a URL that is relative to the
output's root folder. The routing can contain business rules that depend on
the type of FQSEN that is exposed or even what type a specific part of the FQSEN
is.

Example:

    Given the FQSEN ``\My\Space\Element`` it is not possible to determine
    whether this is a Class, Interface, Trait or even a Sub-namespace. To be
    able to determine this it is necessary that we have access to the AST to
    know the meaning of this element and provide the proper route.

The Routing component can have routes for each type of Structural Element, for
reference they are listed here:

* Namespace
* Function
* Constant (global)
* Class
* Interface
* Trait
* Method
* Property
* Constant (class)

Default Router determination
----------------------------

phpDocumentor2 is based upon the principle of convention over configuration
and as such should a default router be assigned to each template. The default
router is based on the version of the ``template.xml``.

Which default router is applicable is determined by a specialized Router Factory
(`RouterFactory`_). This factory is invoked during the instantiation of the
`Template`_ object(s) so that the `Transformer`_ can have access to the router.

By being able to access the Router can the behaviours make use of this object
and thus behaviours will be able to enrich tags with links to other locations.

This factory class attempts to determine the router using 2 business rules:

1. The factory looks up the name of the router class using the version of
   the ``template.xml`` in an internal registry.
2. If the template has a parameter named 'router' then this overrides the
   default class name.

.. important::

   What to do when two templates with a different version are invoked during a
   single run?

.. important::

   The router currently assumes HTML-only templates, a solution needs to be
   worked on for other formats.

Determining the route
---------------------

The `RouterAbstract`_ base class provides the logic used to determine which
route is used given the FQSEN using the `getUrl`_ method. This method executes
the following business rules:

1. if the FQSEN ends in a parenthesis pair, it is either a function or a method

   1. if the FQSEN contains a double colon, it is a method
   2. if not, it is a function.

2. if the FQSEN is separated by a double colon and the second part starts with
   a dollar sign, it is a property.

3. if the FQSEN does not match the above but is separated by a double colon, it
   must be a class constant.

4. if the FQSEN does not match the above, scan all direct child elements of
   each file (class, interface, trait, constant) and if its Fully Qualified
   Name matches the FQSEN, then it is either of these types.

5. if all of the above still does not apply; scan the list of namespaces and
   see whether their FQNN matches the FQSEN; if so it is a namespace.

The above algorithm is optimized for performance by trying to minimize the
number of times the AST needs to be consulted. Accessing the AST is a costly
overhead which must be executed as least as possible.

.. _`Transformer`:    http://demo.phpdoc.org/Responsive/classes/phpDocumentor.Transformer.Transformer.html
.. _`RouterFactory`:  http://demo.phpdoc.org/Responsive/classes/phpDocumentor.Transformer.Router.RouterFactory.html
.. _`RouterAbstract`: http://demo.phpdoc.org/Responsive/classes/phpDocumentor.Transformer.Router.RouterAbstract.html
.. _`getUrl`:         http://demo.phpdoc.org/Responsive/classes/phpDocumentor.Transformer.Router.RouterAbstract.html#method_getUrl
.. _`Template`:       http://demo.phpdoc.org/Responsive/classes/phpDocumentor.Transformer.Template.html