<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2014 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Plugin\Search\Client;

use phpDocumentor\Plugin\Search\Adapter\AdapterInterface;

/**
 * Generates the code that is consumed by a third-party in order to invoke a generator.
 *
 * This class is used by, for example, templates to receive a specific piece of javascript or PHP code
 * that can be used as a client and to interact with the associated engine.
 */
interface GeneratorInterface
{
    const CLIENT_TYPE_BACKEND  = 'backend';
    const CLIENT_TYPE_FRONTEND = 'frontend';

    /**
     * Renders the client code for an engine and returns it.
     *
     * This method allows the caller to generate a piece of client code that can be included, embedded or called in
     * another page. Because most engines have their interfacing split between frontend code (usually Javascript) and
     * a backend script that is invoked (usually PHP) this method can generate a specific type per engine.
     *
     * Specific engines may have types other than 'backend' and 'frontend', please review the documentation for that
     * engine that you want to use for more information.
     *
     * @param AdapterInterface $engine The engine for which to create a client.
     * @param string           $type   The type of client to receive, usually 'backend' or 'frontend'.
     *
     * @return string the client code or an empty string if the given type is not supported for this engine.
     */
    public function generate(AdapterInterface $engine, $type = self::CLIENT_TYPE_BACKEND);
}