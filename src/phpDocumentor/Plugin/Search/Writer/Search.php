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

namespace phpDocumentor\Plugin\Search\Writer;

use phpDocumentor\Descriptor\Collection;
use phpDocumentor\Descriptor\DescriptorAbstract;
use phpDocumentor\Descriptor\ProjectDescriptor;
use phpDocumentor\Plugin\Search\Client\Generator;
use phpDocumentor\Plugin\Search\Document;
use phpDocumentor\Plugin\Search\EngineManager;
use phpDocumentor\Transformer\Transformation;
use phpDocumentor\Transformer\Writer\WriterAbstract;

/**
 * This class populates the search engine, creates a backend PHP file and creates a Javascript client code file
 * (`search.js`) at the target location.
 *
 * The Javascript Client Code will enable a form with the id #search to call on the backend PHP file (or the search
 * engine directly when it is a pure-js engine). The form will then pass the results to a callback that is provided
 * by the template, which in turn can present the results the way it seems fit.
 */
class Search extends WriterAbstract
{
    /** @var EngineManager */
    protected $engineManager;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * Sets the required dependencies for this writer on this object.
     *
     * @param EngineManager     $engineManager
     * @param \Twig_Environment $twig
     */
    public function __construct(EngineManager $engineManager, \Twig_Environment $twig)
    {
        $this->engineManager = $engineManager;
        $this->twig          = $twig;
    }

    /**
     * Populates the search engine and creates the two client files.
     *
     * @param ProjectDescriptor $project        Document containing the structure.
     * @param Transformation    $transformation Transformation to execute.
     *
     * @return void
     */
    public function transform(ProjectDescriptor $project, Transformation $transformation)
    {
        $this->populateSearchEngine($project);
        $this->writeClientToFile($transformation, 'backend', 'search.php');
        $this->writeClientToFile($transformation, 'frontend', 'search.js');
    }

    /**
     * Persists all elements in the project to the Search Engine.
     *
     * @param ProjectDescriptor $project
     *
     * @return void
     */
    protected function populateSearchEngine(ProjectDescriptor $project)
    {
        /** @var DescriptorAbstract $descriptor */
        foreach ($project->getIndexes()->get('elements', new Collection())->getAll() as $descriptor) {
            $this->engineManager->persist(
                $this->populateDocumentWithDescriptor(new Document(), $descriptor)
            );
        }
        $this->engineManager->flush();
    }

    /**
     * Creates a file with the client code at the provided destination.
     *
     * @param Transformation $transformation
     * @param string         $clientType
     * @param string         $destinationFilename
     *
     * @return void
     */
    protected function writeClientToFile(Transformation $transformation, $clientType, $destinationFilename)
    {
        $generator = new Generator($this->twig);
        $this->writeClientCodeToOutputLocation(
            $generator->generate($this->engineManager->getAdapter(), $clientType),
            $transformation,
            $destinationFilename
        );
    }

    /**
     * Populates the provided document with data from the descriptor and returns it.
     *
     * @param Document           $document
     * @param DescriptorAbstract $descriptor
     *
     * @return Document
     */
    protected function populateDocumentWithDescriptor(Document $document, DescriptorAbstract $descriptor)
    {
        $document['fqsen']       = $descriptor->getFullyQualifiedStructuralElementName();
        $document['name']        = $descriptor->getName();
        $document['file']        = $descriptor->getFile();
        $document['summary']     = $descriptor->getSummary();
        $document['description'] = $descriptor->getDescription();

        return $document;
    }

    /**
     * Writes the provides code to its destination location unless the client code is an empty string.
     *
     * @param string         $clientCode
     * @param Transformation $transformation
     * @param string         $destinationFilename
     *
     * @return void
     */
    protected function writeClientCodeToOutputLocation(
        $clientCode,
        Transformation $transformation,
        $destinationFilename
    ) {
        if ($clientCode == '') {
            return;
        }

        $destinationPath = $transformation->getTransformer()->getTarget() . DIRECTORY_SEPARATOR
            . $transformation->getArtifact() . DIRECTORY_SEPARATOR . $destinationFilename;
        file_put_contents($destinationPath, $clientCode);
    }
}
