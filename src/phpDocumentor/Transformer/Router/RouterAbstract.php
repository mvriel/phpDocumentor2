<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
namespace phpDocumentor\Transformer\Router;

/**
 * Represents the most basic version of a Router.
 *
 * The router is responsible for converting FQSEN (Fully Qualified Structural
 * Element Names) into a relative path to a destination in the documentation.
 * This is used with the @see and @uses tag (amongst others) to calculate their
 * destination.
 */
abstract class RouterAbstract
{
    /** @var \DOMDocument */
    protected $ast;

    /**
     * Initializes the router with the AST.
     *
     * @param \DOMDocument $ast
     */
    public function __construct(\DOMDocument $ast)
    {
        $this->ast = $ast;
    }

    /**
     * Tries to retrieve the URL for a given FQSEN.
     *
     * The FQSEN represents a Structural Element somewhere in the element tree;
     * this string will have to be converted based on the
     *
     * @param string $fqsen
     *
     * @return string|boolean
     */
    public function getUrl($fqsen)
    {
        $parts = explode('::', $fqsen);

        if ($this->isFunction($parts)) {
            if ($this->isClassMember($parts)) {
                return $this->getMethodUrl($parts[0], $parts[1]);
            } else {
                return $this->getFunctionUrl($parts[0]);
            }
        } elseif ($this->isProperty($parts)) {
            return $this->getPropertyUrl($parts[0], $parts[1]);
        } elseif ($this->isClassMember($parts)) {
            return $this->getClassConstantUrl($parts[0], $parts[1]);
        }

        // if it is none of the above it much be a namespace, class, interface,
        // trait or global constant; we need the AST to figure that out.
        $xpath = new \DOMXPath($this->ast);
        $result = $xpath->query('/project/file/*[full_name="'.$fqsen.'"]');
        if ($result->length === 1) {
            switch($result->item(0)->nodeName) {
            case 'class':     return $this->getClassUrl($fqsen);
            case 'interface': return $this->getInterfaceUrl($fqsen);
            case 'constant':  return $this->getConstantUrl($fqsen);
            case 'trait':     return $this->getTraitUrl($fqsen);
            }
        }

        // if none still match than it must be a namespace or the FQSEN does not
        // exist in the project.
        $result = $xpath->query(
            '/project/namespace[full_name="' . $fqsen . '"]'
            .'|/project/namespace//namespace[full_name="' . $fqsen . '"]'
        );
        if ($result->length === 1) {
            return $this->getNamespaceUrl($fqsen);
        }

        return false;
    }

    /**
     * Returns the relative URL to the details page of a namespace.
     *
     * @param string $fqnn Fully Qualified Namespace Name, including root slash.
     *
     * @return string
     */
    abstract public function getNamespaceUrl($fqnn);

    /**
     * Returns the relative URL to the details page of a function.
     *
     * @param string $fqfn Fully Qualified Function Name, including root slash.
     *
     * @return string
     */
    abstract public function getFunctionUrl($fqfn);

    /**
     * Returns the relative URL to the details page of a constant.
     *
     * @param string $fqcn Fully Qualified Constant Name, including root slash.
     *
     * @return string
     */
    abstract public function getConstantUrl($fqcn);

    /**
     * Returns the relative URL to the details page of a class.
     *
     * @param string $fqcn Fully Qualified Class Name, including root slash.
     *
     * @return string
     */
    abstract public function getClassUrl($fqcn);

    /**
     * Returns the relative URL to the details page of an interface.
     *
     * @param string $fqcn Fully Qualified Class Name, including root slash.
     *
     * @return string
     */
    abstract public function getInterfaceUrl($fqcn);

    /**
     * Returns the relative URL to the details page of a trait.
     *
     * @param string $fqcn Fully Qualified Class Name, including root slash.
     *
     * @return string
     */
    abstract public function getTraitUrl($fqcn);

    /**
     * Returns the relative URL to the details page of a method in a class.
     *
     * @param string $fqcn        Fully Qualified Class Name, including root
     *     slash.
     * @param string $method_name Name of the method in the class.
     *
     * @return string
     */
    abstract public function getMethodUrl($fqcn, $method_name);

    /**
     * Returns the relative URL to the details page of a property in a class.
     *
     * @param string $fqcn          Fully Qualified Class Name, including root
     *     slash.
     * @param string $property_name Name of the property in the class.
     *
     * @return string
     */
    abstract public function getPropertyUrl($fqcn, $property_name);

    /**
     * Returns the relative URL to the details page of a constant in a class.
     *
     * @param string $fqcn          Fully Qualified Class Name, including root
     *     slash.
     * @param string $constant_name Name of the property in the class.
     *
     * @return string
     */
    abstract public function getClassConstantUrl($fqcn, $constant_name);

    /**
     * Returns whether the given parts of a FQSEN represent a function.
     *
     * @param string[] $parts Is an array containing the FQSEN split by the `::`
     *   sign.
     *
     * @return bool
     */
    private function isFunction($parts)
    {
        return substr($parts[count($parts) - 1], -2) == '()';
    }

    /**
     * Returns whether the given parts of a FQSEN represent a ClassMember.
     *
     * @param string[] $parts Is an array containing the FQSEN split by the `::`
     *   sign.
     *
     * @return bool
     */
    private function isClassMember($parts)
    {
        return count($parts) > 1;
    }

    /**
     * Returns whether the given parts of a FQSEN represent a property.
     *
     * @param string[] $parts Is an array containing the FQSEN split by the `::`
     *   sign.
     *
     * @return bool
     */
    private function isProperty($parts)
    {
        return substr($parts[count($parts) - 1], 1) == '$';
    }
}