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
 * Test class for phpDocumentor\Plugin\Factory.
 *
 * @covers phpDocumentor\Plugin\Factory
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers phpDocumentor\Plugin\Factory::__construct
     */
    public function testInitialize()
    {
        $dispatcher = m::mock('phpDocumentor\Event\Dispatcher');
        $config     = m::mock(new \SimpleXMLElement('<?xml version="1.0" ?><phpdoc></phpdoc>'));

        $factory = new Factory($dispatcher, $config);

        $this->assertAttributeEquals($dispatcher, 'event_dispatcher', $factory);
        $this->assertAttributeEquals($config, 'configuration', $factory);
        $this->assertAttributeInstanceOf('Zend\I18n\Translator\Translator', 'translator', $factory);
    }

    /**
     * @covers phpDocumentor\Plugin\Factory::createFromFile
     */
    public function testCreateFromFile()
    {
        $dispatcher = m::mock('phpDocumentor\Event\Dispatcher');
        $dispatcher->shouldIgnoreMissing();
        $config = m::mock(new \SimpleXMLElement('<?xml version="1.0" ?><phpdoc></phpdoc>'));

        $factory = new Factory($dispatcher, $config);
        $plugin = $factory->createFromFile('Core');

        $this->assertInstanceOf('phpDocumentor\Plugin\Plugin', $plugin);
        $this->assertAttributeEquals('Core', 'name', $plugin);
    }
}
