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

namespace phpDocumentor\Plugin\Search\Tests\Adapter\Configuration;

use Mockery as m;
use phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs;

/**
 * Tests the phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs class.
 */
class LunrJsTest extends \PHPUnit_Framework_TestCase
{
    /** @var LunrJs */
    protected $fixture;

    /** @var string[] */
    protected $schema;

    /** @var string */
    protected $path;

    /**
     * Initializes a new LunrJs configuration object with dependencies.
     */
    protected function setUp()
    {
        $this->schema = array();
        $this->path   = 'path';
        $this->fixture = new LunrJs($this->schema, $this->path);
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs::__construct
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs::getSchema
     */
    public function testSchemaIsReturned()
    {
        $this->assertSame($this->schema, $this->fixture->getSchema());
    }

    /**
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs::__construct
     * @covers phpDocumentor\Plugin\Search\Adapter\Configuration\LunrJs::getPath
     */
    public function testPathIsReturned()
    {
        $this->assertSame($this->path, $this->fixture->getPath());
    }
}
