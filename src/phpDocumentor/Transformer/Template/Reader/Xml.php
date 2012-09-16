<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2012 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
namespace phpDocumentor\Transformer\Template\Reader;

use phpDocumentor\Transformer\Template;
use phpDocumentor\Transformer\Transformer;

/**
 * Object capable of processing an XML string and populating a template.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class Xml extends BaseReader
{
    /**
     * Populates the template with the processed contents and returns it.
     *
     * @param Transformer $transformer
     * @param string      $body_text
     *
     * @return Template
     */
    public function process($body_text)
    {
        $xml = $this->load($body_text);
        $parameters = $this->getParameters($xml);

        $this->template->setAuthor((string)$xml->author);
        $this->template->setVersion((string)$xml->version);

        $transformations = $this->getTransformations($xml);
        foreach ($transformations as $transformation) {
            $transformation_obj = $this->createTransformation($transformation);

            $transformation_obj->setParameters(
                array_merge_recursive(
                    $parameters,
                    $this->getParameters($transformation)
                )
            );

            $this->template->add($transformation_obj);
        }
    }

    protected function createTransformation(\SimpleXMLElement $transformation)
    {
        return new \phpDocumentor\Transformer\Transformation(
            (string)$transformation['writer'],
            (string)$transformation['artifact'],
            (string)$transformation['source'],
            (string)$transformation['query']
        );
    }

    protected function getParameters(\SimpleXMLElement $xml)
    {
        if (isset($xml->parameters) && count($xml->parameters)) {
            return $this->convertSimpleXmlToArray($xml->parameters);
        }

        return array();
    }

    protected function getTransformations($xml)
    {
        $transformations = $xml->transformations
            ? $xml->transformations->transformation
            : array();

        if (!$transformations) {
            $transformations = array();
            return $transformations;
        }
        return $transformations;
    }

    /**
     * Recursive function to convert a SimpleXMLElement to an associative array.
     *
     * @param \SimpleXMLElement $sxml object to convert to a flat array.
     *
     * @return (string|string[])[]
     */
    protected function convertSimpleXmlToArray(\SimpleXMLElement $sxml)
    {
        $result = array();

        /** @var \SimpleXMLElement $value */
        foreach ($sxml->children() as $key => $value) {
            $result[$key] = count($value->children()) > 1
                ? $this->convertSimpleXmlToArray($value)
                : (string)$value;
        }

        return $result;
    }

    /**
     * Validates whether the text is a wellformed XML string adhering to the
     * XSD loading it into a SimpleXMLElement in the process..
     *
     * @param string $body_text
     *
     * @return SimpleXMLElement
     */
    protected function load($body_text)
    {
        $xml = new \SimpleXMLElement($body_text);

// TODO: enable this code once the XSD is generated
//        if (!dom_import_simplexml($xml)->ownerDocument->schemaValidate($xsd)) {
//            throw new \InvalidArgumentException(
//                'The template\'s provided XML does not validate according to '
//                .'the schema'
//            );
//        }

        return $xml;
    }
}
