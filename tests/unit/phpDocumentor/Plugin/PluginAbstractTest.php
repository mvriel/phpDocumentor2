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
 * Test class for phpDocumentor\Plugin\PluginAbstract.
 *
 * @covers phpDocumentor\Plugin\PluginAbstract
 */
class PluginAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers phpDocumentor\Plugin\PluginAbstract::__construct
     */
    public function testInitialize()
    {
        $dispatcher = m::mock('phpDocumentor\Event\Dispatcher');
        $config = m::mock(new \SimpleXMLElement('<?xml version="1.0" ?><phpdoc></phpdoc>'));
        $translator = $this->getMock('Zend\I18n\Translator\Translator');

        $plugin = $this->getMockForAbstractClass(
            'phpDocumentor\Plugin\PluginAbstract',
            array($dispatcher, $config, $translator)
        );

        $this->assertAttributeEquals($dispatcher, 'event_dispatcher', $plugin);
        $this->assertAttributeEquals($config, 'configuration', $plugin);
        $this->assertAttributeEquals($translator, 'translator', $plugin);
    }

    /**
     * @covers phpDocumentor\Plugin\PluginAbstract::__construct
     */
    public function testDispatch()
    {
        $config = m::mock(new \SimpleXMLElement('<?xml version="1.0" ?><phpdoc></phpdoc>'));
        $translator = $this->getMock('Zend\I18n\Translator\Translator');
        $event = m::mock('phpDocumentor\Event\EventAbstract');
        $dispatcher = m::mock('phpDocumentor\Event\Dispatcher')
            ->shouldReceive('dispatch')->once()
            ->with('MyEvent', $event)
            ->andReturn($event)
            ->getMock();

        $plugin = $this->getMockForAbstractClass(
            'phpDocumentor\Plugin\PluginAbstract',
            array(null, $config, $translator)
        );

        $this->assertNull($plugin->dispatch('MyEvent', $event));

        $plugin = $this->getMockForAbstractClass(
            'phpDocumentor\Plugin\PluginAbstract',
            array($dispatcher, $config, $translator)
        );
        $this->assertEquals($event, $plugin->dispatch('MyEvent', $event));
    }

    /**
     * @covers phpDocumentor\Plugin\PluginAbstract::dispatch
     * @expectedException Exception
     */
    public function testDispatchWithInvalidDispatcher()
    {
        $config = m::mock(new \SimpleXMLElement('<?xml version="1.0" ?><phpdoc></phpdoc>'));
        $translator = $this->getMock('Zend\I18n\Translator\Translator');
        $dispatcher = new \stdClass();

        $plugin = $this->getMockForAbstractClass(
            'phpDocumentor\Plugin\PluginAbstract',
            array($dispatcher, $config, $translator)
        );
        $plugin->dispatch('MyEvent', m::mock('phpDocumentor\Event\EventAbstract'));
    }
}
