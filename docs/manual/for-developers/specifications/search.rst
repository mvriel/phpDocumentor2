Search
======

Introduction
------------

This document describes how search should work for phpDocumentor and sibling
projects.

Background
----------

During the early development of phpDocumentor has the search option been removed
because large projects, such as Zend Framework, had large performance issues due
to the size of the JSON index file.

Search, however is an often requested feature and should not be neglected to be
implemented. However a new approach needs to be made with regards to the way
search works.

In addition; with our new sibling project 'Scrybe' has search become even more
important as it is the primary way to search for documents.

Goals
-----

The following goals have been defined as being primary targets to attain:

1. Being able to search a project the size of Zend Framework 2 using
   phpDocumentor2.
2. This must be designed as a re-usable module for other projects, as Scrybe.
3. It must be possible to switch the backend from a simple plain-text index to,
   for example, Zend_Lucene, Solr or ElasticSearch.
4. A plain-text backend needs to be designed where the searching itself is done
   completely in Javascript; the generation of indexes may be in PHP.

   1. Downloading the index in chunks of max 50kb to 100kb (mobile support)
   2. High performance, the time to process a Javascript chunk may not be more
      than 1 second, preferable 0.5s or lower.

5. High performance, the time to page load may not be increased with more
   than 0.3 seconds.

The following secondary goals have been set:

1. Support searching for partial contents on specific fields (such as FQSEN)
2. Support linking multiple systems together (such as the search of
   phpDocumentor and Scrybe).

Definitions
-----------

Backend
    One of the two parts of the Search Engine, executes queries from the
    Frontend and returns the raw results to the Frontend

Builder
    One of the two parts of the Search Engine, converts the data presented by
    the Provider into a searchable format, or index.

Consumer
    Also known as the User, the actor who wants to query the system.

Document
    A searchable collection of information such as a manual page, an element in
    the API, etc.

Expression
    Input by the Consumer that can be fed into the Search Engine's Backend. May
    contain a series of terms and operators to limit the result set by.

Frontend
    Accepts queries from the User, sends them to the Backend and presents the
    results in a clear usable way to the User.

Provider
    Part of the system that populates the Search Engine with Documents and data
    on which to search (fields).

Search Engine
    A system that enables the Frontend to execute queries and receive results.
    Usually consists of 2 distinct parts, the Builder and Backend.

Theory of Operation
-------------------

Search is all about attempting to find a Document using an Expression provided
by the Consumer. But the Search Engine can only return meaningful results if it
is first fed with a series of Documents and its fields containing the data to
search on.

This component provides an abstraction with which it is possible to populate and
consume different Search Engines independent of the application architecture.

A typical usage scenario is that the application provides the Search Engine with
a series of Documents, including data, and that the Consumer is able to query
that Search Engine using the Frontend.

.. code-block::

   <?php
   $document = new Document($id, array(
       'field1' => 'value'
   ));

   $search_engine = new Search\Engine(new Search\Engine\ElasticSearch());
   $search_engine->persist($document);
   $search_engine->flush();

   $results = $search_engine->query($expression);

Search Engines
--------------

Plain-text
~~~~~~~~~~

Stopwords
#########

Instead of collecting a series of stopwords ourselves we have found the list
used by MySQL at http://norm.al/2009/04/14/list-of-english-stop-words/.

a's, able, about, above, according, accordingly, across, actually, after,
afterwards, again, against, ain't, all, allow, allows, almost, alone, along,
already, also, although, always, am, among, amongst, an, and, another, any,
anybody, anyhow, anyone, anything, anyway, anyways, anywhere, apart, appear,
appreciate, appropriate, are, aren't, around, as, aside, ask, asking,
associated, at, available, away, awfully, be, became, because, become, becomes,
becoming, been, before, beforehand, behind, being, believe, below, beside,
besides, best, better, between, beyond, both, brief, but, by, c'mon, c's, came,
can, can't, cannot, cant, cause, causes, certain, certainly, changes, clearly,
co, com, come, comes, concerning, consequently, consider, considering, contain,
containing, contains, corresponding, could, couldn't, course, currently,
definitely, described, despite, did, didn't, different, do, does, doesn't,
doing, don't, done, down, downwards, during, each, edu, eg, eight, either,
else, elsewhere, enough, entirely, especially, et, etc, even, ever, every,
everybody, everyone, everything, everywhere, ex, exactly, example, except, far,
few, fifth, first, five, followed, following, follows, for, former, formerly,
forth, four, from, further, furthermore, get, gets, getting, given, gives, go,
goes, going, gone, got, gotten, greetings, had, hadn't, happens, hardly, has,
hasn't, have, haven't, having, he, he's, hello, help, hence, her, here, here's,
hereafter, hereby, herein, hereupon, hers, herself, hi, him, himself, his,
hither, hopefully, how, howbeit, however, i'd, i'll, i'm, i've, ie, if, ignored,
immediate, in, inasmuch, inc, indeed, indicate, indicated, indicates, inner,
insofar, instead, into, inward, is, isn't, it, it'd, it'll, it's, its, itself,
just, keep, keeps, kept, know, knows, known, last, lately, later, latter,
latterly, least, less, lest, let, let's, like, liked, likely, little, look,
looking, looks, ltd, mainly, many, may, maybe, me, mean, meanwhile, merely,
might, more, moreover, most, mostly, much, must, my, myself, name, namely, nd,
near, nearly, necessary, need, needs, neither, never, nevertheless, new, next,
nine, no, nobody, non, none, noone, nor, normally, not, nothing, novel, now,
nowhere, obviously, of, off, often, oh, ok, okay, old, on, once, one, ones,
only, onto, or, other, others, otherwise, ought, our, ours, ourselves, out,
outside, over, overall, own, particular, particularly, per, perhaps, placed,
please, plus, possible, presumably, probably, provides, que, quite, qv, rather,
rd, re, really, reasonably, regarding, regardless, regards, relatively,
respectively, right, said, same, saw, say, saying, says, second, secondly, see,
seeing, seem, seemed, seeming, seems, seen, self, selves, sensible, sent,
serious, seriously, seven, several, shall, she, should, shouldn't, since, six,
so, some, somebody, somehow, someone, something, sometime, sometimes, somewhat,
somewhere, soon, sorry, specified, specify, specifying, still, sub, such, sup,
sure, t's, take, taken, tell, tends, th, than, thank, thanks, thanx, that,
that's, thats, the, their, theirs, them, themselves, then, thence, there,
there's, thereafter, thereby, therefore, therein, theres, thereupon, these,
they, they'd, they'll, they're, they've, think, third, this, thorough,
thoroughly, those, though, three, through, throughout, thru, thus, to, together,
too, took, toward, towards, tried, tries, truly, try, trying, twice, two, un,
under, unfortunately, unless, unlikely, until, unto, up, upon, us, use, used,
useful, uses, using, usually, value, various, very, via, viz, vs, want, wants,
was, wasn't, way, we, we'd, we'll, we're, we've, welcome, well, went, were,
weren't, what, what's, whatever, when, whence, whenever, where, where's,
whereafter, whereas, whereby, wherein, whereupon, wherever, whether, which,
while, whither, who, who's, whoever, whole, whom, whose, why, will, willing,
wish, with, within, without, won't, wonder, would, would, wouldn't, yes, yet,
you, you'd, you'll, you're, you've, your, yours, yourself, yourselves, zero

Challenges
----------

* Different URL schemas for FQSEN's
* Performance

