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

namespace phpDocumentor\Plugin\Standards\phpDocumentor\Sniff;

use phpDocumentor\Plugin\Standards\phpDocumentor\Constraints\Functions\IsReturnTypeNotAnIdeDefault;
use phpDocumentor\Plugin\Standards\AbstractSniff;

/**
 * Verifies that the type of the `@return` tag in a DocBlock does not match a known IDE placeholder.
 *
 * {@inheritDoc}
 *
 * Certain IDEs have the tendency to add a placeholder type when auto-generating an `@return` tag in a DocBlock. Usually
 * these take the form of a string with the text `type` or `unknown`. This sniff will check for the occurrence of such
 * invalid types and emit a violation if one is found.
 */
class ReturnIsIdeDefault extends AbstractSniff
{
    /**
     * @inheritDoc
     */
    protected function getConstraint()
    {
        return new IsReturnTypeNotAnIdeDefault(array('message' => $this->getName()));
    }
}
