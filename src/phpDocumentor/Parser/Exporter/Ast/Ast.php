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

    /** @var \XMLWriter */
    protected $writer;

    /**
     * Tree representation of all classes subdivided into their namespaces.
     *
     * @var string[]
     */
    protected $namespaces = array(
        '\\' => array()
    );

    protected $files = array();

    /**
     * Initializes this exporter.
     *
     * @return void
     */
    public function initialize()
    {
        $this->writer = new \XMLWriter();
        $this->writer->openUri('structure.xml');
        $this->writer->setIndent(true);
        $this->writer->startDocument('1.0', 'utf-8');

        $this->writer->startElement('project');

        $this->writer->writeAttribute('xmlns', 'http://phpdoc.org/ns/pdast');
        $this->writer->writeAttributeNs('xmlns', 'docbook', null, 'http://docbook.org/ns/docbook');
        $this->writer->writeAttributeNs('xmlns', 'checkstyle', null, 'antlib:com.puppycrawl.tools.checkstyle');
        $this->writer->writeAttribute('version', '1.0.0');
        $this->writer->writeElement('name', $this->parser->getTitle());
    }

    public function finalize()
    {
        $this->exportNamespace('\\', $this->namespaces['\\']);
        foreach ($this->files as $file) {
            $this->writer->writeRaw(stream_get_contents($file));
        }
        $this->writer->endElement();
        $this->writer->endDocument();
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
        $writer->startElement('file');

        $writer->writeAttribute('hash', $file->getHash());
        $writer->writeElement('name', basename($file->getFilename()));
        $writer->writeElement('path', $file->getFilename());
        $this->exportDocBlock($writer, $file->getDocBlock());

        foreach ($file->getNamespaceAliases() as $alias => $namespace) {
            $writer->startElement('namespace_alias');
            $writer->writeElement('name', $alias);
            $writer->writeElement('fqsen', $namespace);
            $writer->endElement();
        }

        foreach ($file->getParseErrors() as $error) {
            list($severity, $message, $line_number, $code) = $error;

            $writer->startElementNs('checkstyle', 'error', null);
            $writer->writeAttribute('line', $line_number);
            $writer->writeAttribute('severity', $severity === 'ERROR' ? 'error' : 'warning');
            $writer->writeAttribute('message', $message);
            $writer->writeAttribute('source', 'org.phpdoc.checkstyle.checks.'.$code);
            $writer->endElement();
        }

        $writer->endElement();

        $stream = fopen('php://temp', 'r+');
        fputs($stream, $writer->outputMemory());
        rewind($stream);

        $this->files[] = $stream;

        /** @var \phpDocumentor\Reflection\ClassReflector $contents */
        foreach ($file->getClasses() as $contents) {
            $class_writer = new \XMLWriter();
            $class_writer->openMemory();
            $class_writer->setIndent(true);

            $this->exportClassInterfaceOrTrait($class_writer, $contents, $file->getFilename());

            $this->storeInNamespaceTree($contents->getNamespace(), $class_writer->outputMemory());
        }

        foreach ($file->getConstants() as $contents) {
            $constant_writer = new \XMLWriter();
            $constant_writer->openMemory();
            $constant_writer->setIndent(true);

            $this->exportConstant($constant_writer, $contents, $file->getFilename());

            $this->storeInNamespaceTree($contents->getNamespace(), $constant_writer->outputMemory());
        }

        foreach ($file->getFunctions() as $contents) {
            $function_writer = new \XMLWriter();
            $function_writer->openMemory();
            $function_writer->setIndent(true);

            $this->exportFunction($function_writer, $contents, $file->getFilename());

            $this->storeInNamespaceTree($contents->getNamespace(), $function_writer->outputMemory());
        }
    }

    protected function exportNamespace($name, $elements)
    {
        $this->writer->startElement('namespace');
        $this->writer->writeElement('name', $name);
        foreach ($elements as $element_name => $element) {
            if (is_array($element)) {
                $this->exportNamespace($element_name, $element);
            } else {
                $this->writer->writeRaw(stream_get_contents($element));
            }
        }
        $this->writer->endElement();
    }


    protected function storeInNamespaceTree($namespace, $contents)
    {
        $stream = fopen('php://temp', 'r+');
        fputs($stream, $contents);
        rewind($stream);

        $tree = &$this->namespaces['\\'];
        $path = explode('\\', $namespace);
        foreach ($path as $location) {
            if (!isset($tree[$location])) {
                $tree[$location] = array();
            }

            $tree = &$tree[$location];
        }

        $tree[] = $stream;
    }

    protected function exportClassInterfaceOrTrait(\XMLWriter $writer, InterfaceReflector $class, $filename)
    {
        $element_name = 'interface';
        if ($class instanceof \phpDocumentor\Reflection\ClassReflector) {
            $element_name = 'class';
        }
        if ($class instanceof \phpDocumentor\Reflection\TraitReflector) {
            $element_name = 'trait';
        }

        $writer->startElement($element_name);

        if ($class instanceof \phpDocumentor\Reflection\ClassReflector) {
            $writer->writeAttribute('final', var_export($class->isFinal(), true));
            $writer->writeAttribute('abstract', var_export($class->isAbstract(), true));
        }
        $writer->writeAttribute('line_number', $class->getLinenumber());
        $writer->writeAttribute('file_name', $filename);

        $writer->writeElement('name', $class->getShortName());
        $writer->writeElement('fqsen', $class->getName());
        $this->exportDocBlock($writer, $class->getDocBlock());

        if ($class instanceof \phpDocumentor\Reflection\ClassReflector) {
            foreach ($class->getConstants() as $constant) {
                $this->exportConstant($writer, $constant);
            }

            foreach ($class->getProperties() as $property) {
                $this->exportProperty($writer, $property);
            }
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
        return null;
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
