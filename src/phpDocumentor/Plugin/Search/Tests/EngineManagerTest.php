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


namespace phpDocumentor\Plugin\Search\Tests;

use Mockery as m;
use phpDocumentor\Plugin\Search\Adapter\AdapterInterface;
use phpDocumentor\Plugin\Search\EngineManager;

class EngineManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var EngineManager  */
    protected $fixture;

    /** @var m\MockInterface|AdapterInterface */
    protected $adapterMock;

    /**
     * Sets up a fixture and mocked adapter dependency.
     */
    protected function setUp()
    {
        $this->adapterMock = m::mock('phpDocumentor\Plugin\Search\Adapter\AdapterInterface');
        $this->fixture = new EngineManager($this->adapterMock);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\EngineManager::__construct
     * @covers phpDocumentor\Plugin\Search\EngineManager::getAdapter
     */
    public function testAdapterIsStoredOnInstantiation()
    {
        // Assert
        $this->assertSame($this->adapterMock, $this->fixture->getAdapter());
    }

    /**
     * @covers phpDocumentor\Plugin\Search\EngineManager::find
     */
    public function testIfFindInvokesAdapter()
    {
        // Arrange
        $expression = 'expression';
        $start      = 0;
        $limit      = 10;
        $response   = 'result';
        $this->adapterMock->shouldReceive('find')->once()->with($expression, $start, $limit)->andReturn($response);

        // Act
        $result = $this->fixture->find($expression, $start, $limit);

        // Assert
        $this->assertSame($response, $result);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\EngineManager::getConfiguration
     */
    public function testIfConfigurationIsRetrievedFromAdapter()
    {
        // Arrange
        $response = 'response';
        $this->adapterMock->shouldReceive('getConfiguration')->once()->andReturn($response);

        // Act
        $result = $this->fixture->getConfiguration();

        // Assert
        $this->assertSame($response, $result);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\EngineManager::persist
     */
    public function testIfDocumentIsPersistedToAdapter()
    {
        // Arrange
        $document = m::mock('phpDocumentor\Plugin\Search\Document');
        $this->adapterMock->shouldReceive('persist')->once()->with($document);

        // Act
        $this->fixture->persist($document);

        // Assert
        $this->assertTrue(true);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\EngineManager::remove
     */
    public function testIfDocumentIsRemovedToAdapter()
    {
        // Arrange
        $document = m::mock('phpDocumentor\Plugin\Search\Document');
        $this->adapterMock->shouldReceive('remove')->once()->with($document);

        // Act
        $this->fixture->remove($document);

        // Assert
        $this->assertTrue(true);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\EngineManager::flush
     */
    public function testIfFlushingIsProxiedToAdapter()
    {
        // Arrange
        $this->adapterMock->shouldReceive('flush')->once();

        // Act
        $this->fixture->flush();

        // Assert
        $this->assertTrue(true);
    }
}
