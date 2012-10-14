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
namespace phpDocumentor\Plugin\Core\Transformer\Router;

use phpDocumentor\Transformer\Router\RouterAbstract;

/**
 * Provides the routing rules for the templates of the same generation
 * as the Responsive template.
 */
class Responsive extends RouterAbstract
{
    /** @var string the extension including dot for these routes */
    const EXTENSION = '.html';

    /**
     * Returns the relative URL to the details page of a namespace.
     *
     * @param string $fqnn Fully Qualified Namespace Name, including root slash.
     *
     * @return string
     */
    public function getNamespaceUrl($fqnn)
    {
        return '/namespaces/' . $this->normalizeNamespace($fqnn)
            . self::EXTENSION;
    }

    /**
     * Returns the relative URL to the details page of a function.
     *
     * @param string $fqfn Fully Qualified Function Name, including root slash.
     *
     * @return string
     */
    public function getFunctionUrl($fqfn)
    {
        $function_name = $this->namespacePop($fqfn);
        $namespace     = $this->getNamespaceUrl($fqfn);

        return $namespace.'#function_' . $function_name;
    }

    /**
     * Returns the relative URL to the details page of a constant.
     *
     * @param string $fqcn Fully Qualified Constant Name, including root slash.
     *
     * @return string
     */
    public function getConstantUrl($fqcn)
    {
        $constant_name = $this->namespacePop($fqcn);
        $namespace     = $this->getNamespaceUrl($fqcn);

        return $namespace . '#constant_' . $constant_name;
    }

    /**
     * Returns the relative URL to the details page of a class.
     *
     * @param string $fqcn Fully Qualified Class Name, including root slash.
     *
     * @return string
     */
    public function getClassUrl($fqcn)
    {
        return '/classes/' . $this->normalizeNamespace($fqnn)
            . self::EXTENSION;
    }

    /**
     * Returns the relative URL to the details page of an interface.
     *
     * @param string $fqcn Fully Qualified Class Name, including root slash.
     *
     * @return string
     */
    public function getInterfaceUrl($fqcn)
    {
        return $this->getClassUrl($fqcn);
    }

    /**
     * Returns the relative URL to the details page of a trait.
     *
     * @param string $fqcn Fully Qualified Class Name, including root slash.
     *
     * @return string
     */
    public function getTraitUrl($fqcn)
    {
        return $this->getClassUrl($fqcn);
    }

    /**
     * Returns the relative URL to the details page of a method in a class.
     *
     * @param string $fqcn        Fully Qualified Class Name, including root
     *     slash.
     * @param string $method_name Name of the method in the class.
     *
     * @return string
     */
    public function getMethodUrl($fqcn, $method_name)
    {
        return $this->getClassUrl($fqcn) . '#method_' . $method_name;
    }

    /**
     * Returns the relative URL to the details page of a property in a class.
     *
     * @param string $fqcn          Fully Qualified Class Name, including root
     *     slash.
     * @param string $property_name Name of the property in the class.
     *
     * @return string
     */
    public function getPropertyUrl($fqcn, $property_name)
    {
        return $this->getClassUrl($fqcn) . '#property' . $property_name;
    }

    /**
     * Returns the relative URL to the details page of a constant in a class.
     *
     * @param string $fqcn          Fully Qualified Class Name, including root
     *     slash.
     * @param string $constant_name Name of the property in the class.
     *
     * @return string
     */
    public function getClassConstantUrl($fqcn, $constant_name)
    {
        return $this->getClassUrl($fqcn) . '#constant_' . $constant_name;
    }

    /**
     * Removes the last part of the namespace and returns it.
     *
     * @param string &$fqnn Fully Qualified Namespace Name, including root
     *     slash.
     *
     * @see array_pop() for the same functionality with regards to arrays.
     *
     * @return string|boolean
     */
    protected function namespacePop(&$fqnn)
    {
        $fqnn          = explode('\\', $fqnn);
        $function_name = array_pop($fqnn);
        $fqnn          = implode('\\', $fqnn);

        return $function_name;
    }

    /**
     * Transforms a Fully Qualified Namespace Name into its parts with
     * dots in between.
     *
     * @param string $fqnn Fully Qualified Namespace Name, including root slash.
     *
     * @return string
     */
    protected function normalizeNamespace($fqnn)
    {
        return str_replace('\\', '.', ltrim($fqnn));
    }

}
