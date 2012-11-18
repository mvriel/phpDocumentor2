<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2012 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Parser\Exporter\Ast;

use phpDocumentor\Parser\Exporter\ExporterAbstract;
use phpDocumentor\Reflection\FileReflector;
use phpDocumentor\Reflection\InterfaceReflector;
use phpDocumentor\Reflection\ConstantReflector;
use phpDocumentor\Reflection\FunctionReflector;
use phpDocumentor\Reflection\ClassReflector\PropertyReflector;
use phpDocumentor\Reflection\ClassReflector\MethodReflector;
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
//        $this->xml = new \DOMDocument('1.0', 'utf-8');
//        $this->xml->formatOutput = true;
//
//        $document_element = new \DOMElement('project');
//        $this->xml->appendChild($document_element);
//
//        $document_element->setAttribute('version', '1.0.0');
//
//        $name = new \DOMElement('name', $this->parser->getTitle());
//        $document_element->appendChild($name);
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
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->startDocument('1.0', 'utf-8');
        $writer->startElement('file');

        $writer->writeElement('name', basename($file->getFilename()));
        $writer->writeElement('path', $file->getFilename());
        $this->exportDocBlock($writer, $file->getDocBlock());

        foreach ($file->getNamespaceAliases() as $alias => $namespace) {
            $writer->startElement('namespace_alias');
            $writer->writeElement('name', $alias);
            $writer->writeElement('fqsen', $namespace);
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();

        foreach ($file->getClasses() as $contents) {
            $class_writer = new \XMLWriter();
            $class_writer->openMemory();
            $class_writer->setIndent(true);
            $class_writer->startDocument('1.0', 'utf-8');

            $this->exportClass($class_writer, $contents, $file->getFilename());

            $class_writer->endDocument();
            var_dump($class_writer->outputMemory());
        }

        foreach ($file->getConstants() as $contents) {
            $constant_writer = new \XMLWriter();
            $constant_writer->openMemory();
            $constant_writer->setIndent(true);
            $constant_writer->startDocument('1.0', 'utf-8');

            $this->exportConstant($constant_writer, $contents, $file->getFilename());

            $constant_writer->endDocument();
            var_dump($constant_writer->outputMemory());
        }

        foreach ($file->getFunctions() as $contents) {
            $function_writer = new \XMLWriter();
            $function_writer->openMemory();
            $function_writer->setIndent(true);
            $function_writer->startDocument('1.0', 'utf-8');

            $this->exportFunction($function_writer, $contents, $file->getFilename());

            $function_writer->endDocument();
            var_dump($function_writer->outputMemory());
        }

//        var_dump($writer->outputMemory());
    }

    protected function exportClass(\XMLWriter $writer, InterfaceReflector $class, $filename)
    {
        $element_name = 'interface';
        if ($class instanceof \phpDocumentor\Reflection\ClassReflector) {
            $element_name = 'class';
        }
        if ($class instanceof \phpDocumentor\Reflection\TraitReflector) {
            $element_name = 'trait';
        }

        $writer->startElement($element_name);

        $writer->writeAttribute('final', var_export($class->isFinal(), true));
        $writer->writeAttribute('abstract', var_export($class->isAbstract(), true));
        $writer->writeAttribute('line_number', $class->getLinenumber());
        $writer->writeAttribute('file_name', $filename);

        $writer->writeElement('name', $class->getShortName());
        $writer->writeElement('fqsen', $class->getName());
        $this->exportDocBlock($writer, $class->getDocBlock());

        foreach ($class->getConstants() as $constant) {
            $this->exportConstant($writer, $constant);
        }

        foreach ($class->getProperties() as $property) {
            $this->exportProperty($writer, $property);
        }

        foreach ($class->getMethods() as $method) {
            $this->exportMethod($writer, $method);
        }

        $writer->endElement();
    }

    protected function exportConstant(\XMLWriter $writer, ConstantReflector $constant, $filename = null)
    {
        $writer->startElement('constant');

        $writer->writeAttribute('line_number', $constant->getLinenumber());
        if ($filename) {
            $writer->writeAttribute('file_name', $filename);
        }

        $writer->writeElement('name', $constant->getName());
        $writer->writeElement('fqsen', '');
        $this->exportDocBlock($writer, $constant->getDocBlock());

        $writer->endElement();
    }

    protected function exportProperty(\XMLWriter $writer, PropertyReflector $property)
    {
        $writer->startElement('property');

        $writer->writeAttribute('static', var_export($property->isStatic(), true));
        $writer->writeAttribute('line_number', $property->getLinenumber());

        $writer->writeElement('name', $property->getName());
        $writer->writeElement('fqsen', '');
        $this->exportDocBlock($writer, $property->getDocBlock());

        $writer->endElement();
    }

    protected function exportMethod(\XMLWriter $writer, MethodReflector $method)
    {
        $writer->startElement('method');

        $writer->writeAttribute('static', var_export($method->isStatic(), true));
        $writer->writeAttribute('final', var_export($method->isFinal(), true));
        $writer->writeAttribute('abstract', var_export($method->isAbstract(), true));
        $writer->writeAttribute('line_number', $method->getLinenumber());

        $writer->writeElement('name', $method->getName());
        $writer->writeElement('fqsen', '');
        $this->exportDocBlock($writer, $method->getDocBlock());

        $writer->endElement();
    }

    protected function exportFunction(\XMLWriter $writer, FunctionReflector $method, $filename)
    {
        $writer->startElement('function');

        $writer->writeAttribute('file_name', $filename);
        $writer->writeAttribute('line_number', $method->getLinenumber());

        $writer->writeElement('name', $method->getName());
        $writer->writeElement('fqsen', '');
        $this->exportDocBlock($writer, $method->getDocBlock());

        $writer->endElement();
    }

    /**
     * Returns the contents of this export or null if contents were directly
     * written to disk.
     *
     * @return string|null
     */
    public function getContents()
    {
//        return $this->xml->saveXML();
    }

    protected function exportDocBlock(\XMLWriter $writer, DocBlock $docblock = null)
    {
        if (!$docblock) {
            return;
        }

        $this->writeFormattedText($writer, 'summary', $docblock->getShortDescription());
        $this->writeFormattedText($writer, 'description', $docblock->getLongDescription()->getContents());
    }

    protected function writeFormattedText(\XMLWriter $writer, $name, $text)
    {
        $writer->startElement($name);
        $writer->writeElementNs('docbook', 'para', null, $text);
        $writer->endElement();
    }
}
