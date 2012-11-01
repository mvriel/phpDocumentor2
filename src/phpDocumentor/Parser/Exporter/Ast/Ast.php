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

namespace phpDocumentor\Parser\Exporter\Ast;

use phpDocumentor\Parser\Exporter\ExporterAbstract;
use phpDocumentor\Reflection\FileReflector;
use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock;

class Ast extends ExporterAbstract
{
    /** @var \DOMDocument $xml Document containing the collected data */
    protected $xml;

    /**
     * Initializes this exporter.
     *
     * @return void
     */
    public function initialize()
    {
        $this->xml = new \DOMDocument('1.0', 'utf-8');
        $this->xml->formatOutput = true;

        $document_element = new \DOMElement('project');
        $this->xml->appendChild($document_element);

        $document_element->setAttribute('version', '1.0.0');

        $name = new \DOMElement('name', $this->parser->getTitle());
        $document_element->appendChild($name);
    }

    /**
     * Renders the reflected file to a structure file.
     *
     * @param FileReflector $file File to export.
     *
     * @return void
     */
    public function export($file)
    {
        $file_node = new \DOMElement('file');
        $this->xml->documentElement->appendChild($file_node);
        $file_node->appendChild(
            new \DOMElement('name', basename($file->getFilename()))
        );
        $this->exportDocBlock($file_node, $file->getDocBlock());
        $file_node->appendChild(new \DOMElement('path', $file->getFilename()));

        foreach($file->getNamespaceAliases() as $alias => $namespace) {
            $alias_node = new \DOMElement('namespace-alias');
            $file_node->appendChild($alias_node);

            $alias_node->appendChild(new \DOMElement('name', $alias));
            $alias_node->appendChild(new \DOMElement('fqsen', $namespace));
        }

        foreach($file->getClasses() as $class) {
            $this->exportClass($file_node, $class);
        }
    }

    protected function exportClass(
        \DOMElement $file_node, ClassReflector $class
    ) {

    }

    /**
     * Returns the contents of this export or null if contents were directly
     * written to disk.
     *
     * @return string|null
     */
    public function getContents()
    {
        return $this->xml->saveXML();
    }

    protected function exportDocBlock(
        \DOMElement $element, DocBlock $docblock = null
    ) {
        if (!$docblock) {
            return;
        }

        $element->appendChild(
            new \DOMElement('summary', $docblock->getShortDescription())
        );
        $element->appendChild(
            new \DOMElement(
                'description', $docblock->getLongDescription()->getContents()
            )
        );
    }

}
