<?php
/**
 * phpDocumentor
 *
 * PHP Version 5
 *
 * @category   phpDocumentor
 * @package    Parser
 * @subpackage Tag_Definitions
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @copyright  2010-2011 Mike van Riel / Naenius. (http://www.naenius.com)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://phpdoc.org
 */

namespace phpDocumentor\Plugin\Core\Parser\DocBlock\Tag\Definition;

/**
 * Definition for the @see tag; expands the class mentioned in the refers
 * attribute.
 *
 * @category   phpDocumentor
 * @package    Parser
 * @subpackage Tag_Definitions
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://phpdoc.org
 */
use phpDocumentor\Reflection\DocBlock\Type\Collection;
use phpDocumentor\Reflection\DocBlock\Context;

class See extends Definition
{
    /**
     * Adds a new attribute `refers` to the structure element for this tag and
     * set the description to the element name.
     *
     * @return void
     */
    protected function configure()
    {
        $referral = explode('::', $this->xml->getAttribute('refers'));

        $types = new Collection(array($referral[0]), new Context($this->getNamespace(), $this->getNamespaceAliases()));
        $referral[0] = (string)$types;
        $this->xml->setAttribute('refers', $referral = implode('::', $referral));
        if (trim($this->xml->getAttribute('description')) === '') {
            $this->xml->setAttribute('description', $referral);
        }
    }
}
