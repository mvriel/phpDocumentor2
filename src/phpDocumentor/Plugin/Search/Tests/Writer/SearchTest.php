<?php

namespace phpDocumentor\Plugin\Search\Tests\Writer;

use Mockery as m;
use phpDocumentor\Descriptor\Collection;
use phpDocumentor\Descriptor\DescriptorAbstract;
use phpDocumentor\Descriptor\ProjectDescriptor;
use phpDocumentor\Plugin\Search\EngineManager;
use phpDocumentor\Plugin\Search\Writer\Search;

class SearchTest extends \PHPUnit_Framework_TestCase
{
    /** @var Search */
    private $fixture;

    /** @var EngineManager|m\MockInterface */
    private $engineManagerMock;

    /** @var \Twig_Environment|m\MockInterface */
    private $twigMock;

    /**
     * Sets up the fixture and its dependencies.
     */
    protected function setUp()
    {
        $this->engineManagerMock = m::mock('phpDocumentor\Plugin\Search\EngineManager');
        $this->twigMock = m::mock('\Twig_Environment');
        $this->fixture = new Search($this->engineManagerMock, $this->twigMock);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Writer\Search::__construct
     */
    public function testIfDependenciesAreRegisteredUponInitialization()
    {
        $this->assertAttributeSame($this->engineManagerMock, 'engineManager', $this->fixture);
        $this->assertAttributeSame($this->twigMock, 'twig', $this->fixture);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Writer\Search::transform
     * @covers phpDocumentor\Plugin\Search\Writer\Search::populateSearchEngine
     * @covers phpDocumentor\Plugin\Search\Writer\Search::populateDocumentWithDescriptor
     */
    public function testIfSearchEngineIsPopulatedDuringTransformation()
    {
        // Arrange
        $projectDescriptorMock = $this->givenAProjectDescriptor();
        $this->whenProjectDescriptorHasTwoDescriptors($projectDescriptorMock);
        $this->thenEngineManagerShouldPersistTwoDocuments();
        $this->thenEngineManagerShouldFlushDocumentsToEngine();
        $this->whenEngineManagerProvidesAnElasticSearchAdapter();

        // Act
        $this->fixture->transform($projectDescriptorMock, m::mock('phpDocumentor\Transformer\Transformation'));

        // Assert
        $this->assertTrue(true);
    }

    /**
     * Returns a bare Project Descriptor used for testing.
     *
     * @return ProjectDescriptor
     */
    protected function givenAProjectDescriptor()
    {
        return new ProjectDescriptor('name');
    }

    /**
     * Sets an elements index containing two descriptor mocks.
     *
     * @param ProjectDescriptor $projectDescriptorMock
     *
     * @return void
     */
    protected function whenProjectDescriptorHasTwoDescriptors(ProjectDescriptor $projectDescriptorMock)
    {
        $projectDescriptorMock->getIndexes()->get(
            'elements',
            new Collection(
                array(
                    $this->givenADescriptor('Descriptor'),
                    $this->givenADescriptor('Descriptor2'),
                )
            )
        );
    }

    /**
     * Asserts that the EngineManager mock has its persist method called twice with the test documents.
     *
     * @return void
     */
    protected function thenEngineManagerShouldPersistTwoDocuments()
    {
        $this->engineManagerMock->shouldReceive('persist')->twice()
            ->with(m::type('phpDocumentor\Plugin\Search\Document'));
    }

    /**
     * Asserts that the flush method is called on the EngineManager mock.
     *
     * @return void
     */
    protected function thenEngineManagerShouldFlushDocumentsToEngine()
    {
        $this->engineManagerMock->shouldReceive('flush')->once();
    }

    /**
     * Haves the Engine Manager mock return an Elastic Search adapter.
     *
     * @return void
     */
    protected function whenEngineManagerProvidesAnElasticSearchAdapter()
    {
        $adapterMock = m::mock('phpDocumentor\Plugin\Search\Adapter\ElasticSearch');
        $adapterMock->shouldReceive('getConfiguration')->andReturn(new \stdClass());
        $this->engineManagerMock->shouldReceive('getAdapter')->andReturn($adapterMock);
    }

    /**
     * Returns a mock Descriptor with the given name.
     *
     * @param string $descriptorName
     *
     * @return DescriptorAbstract|m\MockInterface
     */
    protected function givenADescriptor($descriptorName)
    {
        return m::mock('phpDocumentor\Descriptor\DescriptorAbstract')
            ->shouldReceive('getFullyQualifiedStructuralElementName')->andReturn('\\My\\' . $descriptorName)
            ->shouldReceive('getName')->andReturn($descriptorName)
            ->shouldReceive('getFile')->andReturn('File')
            ->shouldReceive('getSummary')->andReturn('Summary')
            ->shouldReceive('getDescription')->andReturn('Description')
            ->getMock();
    }
}
