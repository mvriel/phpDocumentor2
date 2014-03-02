<?php

namespace phpDocumentor\Plugin\Search\Tests\Client;

use Mockery as m;
use phpDocumentor\Plugin\Search\Adapter\ElasticSearch;
use phpDocumentor\Plugin\Search\Client\Generator;
use phpDocumentor\Plugin\Search\Client\GeneratorInterface;

/**
 * Tests for the phpDocumentor\Plugin\Search\Client\Generator class.
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var Generator */
    private $fixture;

    /** @var \Twig_Environment|m\MockInterface */
    private $twigMock;

    /**
     * Initializes the fixture and Twig dependency.
     */
    protected function setUp()
    {
        $this->twigMock = m::mock('Twig_Environment');
        $this->fixture  = new Generator($this->twigMock);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Client\Generator::__construct
     */
    public function testIfDependenciesAreRegisteredUponInitialization()
    {
        $this->assertAttributeEquals($this->twigMock, 'twig', $this->fixture);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Client\Generator::generate
     * @covers phpDocumentor\Plugin\Search\Client\Generator::generateClientFromTemplateWithConfiguration
     * @covers phpDocumentor\Plugin\Search\Client\Generator::getTemplateFolder
     * @covers phpDocumentor\Plugin\Search\Client\Generator::getTemplateFilename
     * @covers phpDocumentor\Plugin\Search\Client\Generator::findEngineType
     */
    public function testIfBackendClientCodeIsGenerated()
    {
        // Arrange
        $type        = GeneratorInterface::CLIENT_TYPE_BACKEND;
        $expected    = 'abc';
        $adapterMock = $this->givenAnElasticSearchEngine();
        $this->thenTwigShouldRenderUsingTheConfiguration($adapterMock->getConfiguration(), $expected);

        // Act
        $result = $this->fixture->generate($adapterMock, $type);

        // Assert
        $this->assertInternalType('string', $result);
        $this->assertSame($expected, $result);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Client\Generator::generate
     * @covers phpDocumentor\Plugin\Search\Client\Generator::generateClientFromTemplateWithConfiguration
     */
    public function testIfClientWithUnknownTypeReturnsAnEmptyString()
    {
        // Arrange
        $type        = '_unknownType';
        $adapterMock = $this->givenAnElasticSearchEngine();

        // Act
        $result = $this->fixture->generate($adapterMock, $type);

        // Assert
        $this->assertInternalType('string', $result);
        $this->assertEmpty($result);
    }

    /**
     * Instantiates an ElasticSearch adapter with a mocked configuration and returns those.
     *
     * @return ElasticSearch
     */
    private function givenAnElasticSearchEngine()
    {
        $configurationClass = 'phpDocumentor\Plugin\Search\Adapter\Configuration\ElasticSearch';
        $configurationMock = m::mock($configurationClass)->shouldIgnoreMissing();
        $adapterMock = new ElasticSearch($configurationMock);
        return $adapterMock;
    }

    /**
     * Configures the Twig mock to receive a setLoader and render call and return the expected value.
     *
     * @param m\MockInterface $configurationMock
     * @param string          $expected
     *
     * @return void
     */
    private function thenTwigShouldRenderUsingTheConfiguration($configurationMock, $expected)
    {
        $this->twigMock->shouldReceive('setLoader')->andReturnNull();
        $this->twigMock->shouldReceive('render')
            ->with(m::type('string'), array('configuration' => $configurationMock))->andReturn($expected);
    }
}