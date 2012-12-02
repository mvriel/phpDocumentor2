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

namespace phpDocumentor\Plugin;

use Mockery as m;

/**
 * Test class for phpDocumentor\Plugin\Collection.
 *
 * @covers phpDocumentor\Plugin\Collection
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers phpDocumentor\Plugin\Collection::load
     */
    public function testLoad()
    {
        $configuration = array('Core');

        $plugin  = m::mock('phpDocumentor\Plugin\Plugin');
        $factory = m::mock('phpDocumentor\Plugin\Factory')
            ->shouldReceive('createFromFile')->once()
            ->with('Core')
            ->andReturn($plugin)
            ->getMock();

        $collection = new Collection();
        $this->assertCount(0, $collection);

        $collection->load($factory, $configuration);
        $this->assertCount(1, $collection);
        $this->assertContains($plugin, $collection);
    }

    /**
     * @covers phpDocumentor\Plugin\Collection::load
     */
    public function testLoadWithEmptyConfiguration()
    {
        $configuration = array();

        $plugin  = m::mock('phpDocumentor\Plugin\Plugin');
        $factory = m::mock('phpDocumentor\Plugin\Factory')
            ->shouldReceive('createFromFile')->once()
            ->with('Core')
            ->andReturn($plugin)
            ->getMock();

        $collection = new Collection();
        $this->assertCount(0, $collection);

        $collection->load($factory, $configuration);
        $this->assertCount(1, $collection);
        $this->assertContains($plugin, $collection);
    }

}
