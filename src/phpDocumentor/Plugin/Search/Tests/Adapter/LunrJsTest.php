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

namespace phpDocumentor\Plugin\Search\Tests\Adapter;

use Mockery as m;
use phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs as LunrJsConfiguration;
use phpDocumentor\Plugin\Search\Adapter\LunrJs;
use phpDocumentor\Plugin\Search\Document;

/**
 * Tests the phpDocumentor\Plugin\Search\Adapter\LunrJs class.
 */
class LunrJsTest extends \PHPUnit_Framework_TestCase
{
    /** @var LunrJs */
    private $fixture;

    /** @var LunrJsConfiguration|m\MockInterface */
    private $configurationMock;

    /**
     * Initializes a fixture including mocked configuration dependency.
     */
    protected function setUp()
    {
        $this->configurationMock = m::mock('phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs');
        $this->fixture = new LunrJs($this->configurationMock);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::__construct
     */
    public function testConfigurationIsRegisteredOnInitialization()
    {
        $this->assertAttributeSame($this->configurationMock, 'configuration', $this->fixture);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::getConfiguration
     */
    public function testIfConfigurationCanBeRetrieved()
    {
        $this->assertSame($this->configurationMock, $this->fixture->getConfiguration());
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::find
     * @expectedException \RuntimeException
     */
    public function testFindFailsBecauseLunrDoesNotSupportQueryingOnServer()
    {
        $this->fixture->find('expression');
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::persist
     */
    public function testPersistADocumentQueuesItForFlush()
    {
        $document = $this->givenExampleDocument();

        $this->fixture->persist($document);

        $this->assertAttributeSame(array(1 => $document), 'updates', $this->fixture);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::persist
     */
    public function testPersistingADocumentAgainReplacesPrevious()
    {
        $document = $this->givenExampleDocument();

        $this->fixture->persist($document);
        $this->fixture->persist($document);

        $this->assertAttributeSame(array(1 => $document), 'updates', $this->fixture);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::flush
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::getIndexPath
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::generateEngineInstantiation
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::generateSchema
     * @covers phpDocumentor\Plugin\Search\Adapter\LunrJs::convert
     */
    public function testFlushGeneratesAJavascriptIndexFile()
    {
        // Arrange
        $document = $this->givenExampleDocument();
        $this->givenConfigurationPointsToTempFolder();
        $this->configurationMock->shouldReceive('getSchema')->andReturn(array(array('name' => 'testField')));
        $this->fixture->persist($document);

        // Act
        $this->fixture->flush();
        $result = file_get_contents(sys_get_temp_dir() . DIRECTORY_SEPARATOR . LunrJs::INDEX_FILENAME);

        $expected = <<<JS
var lunrIndex = lunr(function() {this.ref("id");this.field("testField");});lunrIndex.add({"id":1,"testField":1});
JS;

        $this->assertSame($expected, $result);
    }

    /**
     * @return Document
     */
    protected function givenExampleDocument()
    {
        $document = new Document();
        $document->setId(1);
        $document['testField'] = 1;
        return $document;
    }

    protected function givenConfigurationPointsToTempFolder()
    {
        $this->configurationMock->shouldReceive('getPath')->andReturn(sys_get_temp_dir());
    }
}