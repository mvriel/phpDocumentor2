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

namespace phpDocumentor\Plugin\Core\Transformer\Writer;

use phpDocumentor\Transformer\Writer\WriterAbstract;
use phpDocumentor\Transformer\Transformation;
use phpDocumentor\Search\Engine\ElasticSearch;
use phpDocumentor\Search\Engine\Configuration;
use phpDocumentor\Search\Mapper;
use \phpDocumentor\Search\Document;
use Guzzle\Http\Client;

/**
 * Search writer responsible for building the search index.
 */
class Search extends WriterAbstract
{
    /**
     * Creates the search index at the artifact location.
     *
     * @param \DOMDocument   $structure      Structure source use as basis for the transformation.
     * @param Transformation $transformation Transformation that supplies the meta-data for this writer.
     *
     * @return void
     */
    public function transform(\DOMDocument $structure, Transformation $transformation)
    {
        if (!$transformation->getSource()) {
            $this->createXmlIndex(
                $structure,
                $transformation->getTransformer()->getTarget() . DIRECTORY_SEPARATOR . $transformation->getArtifact()
            );
        } else {
            $engine    = $this->createElasticSearchEngine($transformation);

            $node_list = $this->discoverStructuralElementNodes($structure);
            $mapper    = new Mapper($transformation->getParameter('mapping', array()));

            // persist all dom elements
            for ($i = 0; $i < $node_list->length; $i++) {
                $document = $this->generateDocument($mapper, simplexml_import_dom($node_list->item($i)));
                $engine->persist($document);
            }

            // send the data to the search engine
            $this->log('Sending data to the Search Engine');
            $engine->flush();
        }
    }

    /**
     * Retrieves all nodes referring to structural elements from the AST.
     *
     * @param \DOMDocument $structure
     *
     * @return \DOMNodeList
     */
    protected function discoverStructuralElementNodes(\DOMDocument $structure)
    {
        $xpath = new \DOMXPath($structure);
        return $xpath->query(
            '/project/file/class|/project/file/interface|/project/file/trait|/project/file/function'
            . '|/project/file/constant|/project/file/*/property|/project/file/*/method|/project/file/*/constant'
        );
    }

    /**
     * Creates an instance of the ElasticSearch engine using parameters from the transformation.
     *
     * @param Transformation $transformation
     *
     * @return ElasticSearch
     */
    protected function createElasticSearchEngine(Transformation $transformation)
    {
        $configuration = new Configuration\ElasticSearch(
            new Client(),
            $transformation->getParameter('uri', 'http://localhost:9200')
        );
        $configuration->setIndex($transformation->getParameter('index', 'api'));
        $configuration->setType($transformation->getParameter('type', 'documentation'));

        // instantiate engine
        return new ElasticSearch($configuration);
    }

    /**
     * Populates a new document with data from the $node using the provided mapper.
     *
     * @param Mapper            $mapper
     * @param \SimpleXMLElement $node
     *
     * @return Document
     */
    protected function generateDocument(Mapper $mapper, \SimpleXMLElement $node)
    {
        $document = new Document();
        $document->setId($this->generateFqsen($node));
        $mapper->populate($document, $node);

        return $document;
    }

    /**
     * Generates a FQSEN with the provided structural element node.
     *
     * @param \SimpleXMLElement $node
     *
     * @return string
     */
    protected function generateFqsen(\SimpleXMLElement $node)
    {
        switch($node->getName()) {
            case 'class':
            case 'interface':
            case 'trait':
                return (string)$node->full_name;
            case 'function':
                return (string)$node->full_name.'()';
            case 'method':
                /** @var \SimpleXMLElement $parent_node  */
                $parent_node = current($node->xpath("parent::*"));
                $fqcn        = (string)$parent_node->full_name;
                return $fqcn . '::'  . (string)$node->name.'()';
            case 'property':
                /** @var \SimpleXMLElement $parent_node  */
                $parent_node = current($node->xpath("parent::*"));
                $fqcn        = (string)$parent_node->full_name;
                return $fqcn . '::'  . (string)$node->name;
            case 'constant':
                /** @var \SimpleXMLElement $parent_node  */
                $parent_node = current($node->xpath("parent::*"));
                $fqcn        = (string)$parent_node->full_name;
                return (in_array($parent_node->getName(), array('class', 'interface', 'trait')) ? $fqcn . '::' : '')
                    . (string)$node->name;
            default:
                break;
        }
    }

    /**
     * Helper method to create the actual index.
     *
     * @param \DOMDocument $xml         Structure source use as basis for the transformation.
     * @param string      $target_path The path where to generate the index.
     *
     * @todo refactor this method to be smaller and less complex.
     *
     * @return void
     */
    public function createXmlIndex(\DOMDocument $xml, $target_path)
    {
        $this->log('Generating the search index');

        $output = new \SimpleXMLElement('<nodes></nodes>');
        $xml = simplexml_import_dom($xml);

        foreach ($xml->file as $file) {
            foreach ($file->interface as $interface) {
                $interface_node = $output->addChild('node');
                $interface_node->value = (string)$interface->full_name;
                $interface_node->id    = $file['generated-path'] . '#' . $interface_node->value;
                $interface_node->type  = 'interface';

                foreach ($interface->constant as $constant) {
                    $node = $output->addChild('node');
                    $node->value = (string)$interface->full_name . '::' . (string)$constant->name;
                    $node->id    = $file['generated-path'] . '#' . $node->value;
                    $node->type  = 'constant';
                }

                foreach ($interface->property as $property) {
                    $node = $output->addChild('node');
                    $node->value = (string)$interface->full_name . '::' . (string)$property->name;
                    $node->id    = $file['generated-path'] . '#' . $node->value;
                    $node->type  = 'property';
                }

                foreach ($interface->method as $method) {
                    $node = $output->addChild('node');
                    $node->value = (string)$interface->full_name . '::' . (string)$method->name . '()';
                    $node->id    = $file['generated-path'] . '#' . $node->value;
                    $node->type  = 'method';
                }
            }

            foreach ($file->class as $class) {
                $class_node = $output->addChild('node');
                $class_node->value = (string)$class->full_name;
                $class_node->id    = $file['generated-path'] . '#' . $class_node->value;
                $class_node->type  = 'class';

                foreach ($class->constant as $constant) {
                    $node = $output->addChild('node');
                    $node->value = (string)$class->full_name . '::' . (string)$constant->name;
                    $node->id    = $file['generated-path'] . '#' . $node->value;
                    $node->type  = 'constant';
                }

                foreach ($class->property as $property) {
                    $node = $output->addChild('node');
                    $node->value = (string)$class->full_name . '::' . (string)$property->name;
                    $node->id    = $file['generated-path'] . '#' . $node->value;
                    $node->type  = 'property';
                }

                foreach ($class->method as $method) {
                    $node = $output->addChild('node');
                    $node->value = (string)$class->full_name . '::' . (string)$method->name . '()';
                    $node->id    = $file['generated-path'] . '#' . $node->value;
                    $node->type  = 'method';
                }
            }

            foreach ($file->constant as $constant) {
                $node = $output->addChild('node');
                $node->value = (string)$constant->name;
                $node->id    = $file['generated-path'] . '#::' . $node->value;
                $node->type  = 'constant';
            }

            foreach ($file->function as $function) {
                $node = $output->addChild('node');
                $node->value = (string)$function->name . '()';
                $node->id = $file['generated-path'] . '#::' . $node->value;
                $node->type = 'function';
            }
        }

        $output->asXML($target_path . '/search_index.xml');
    }
}
