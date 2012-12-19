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
 * Test class for phpDocumentor\Plugin\ListenerAbstract.
 *
 * @covers phpDocumentor\Plugin\ListenerAbstract
 */
class ListenerAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $dispatcher = m::mock('phpDocumentor\Event\Dispatcher');
        $config = m::mock(new \SimpleXMLElement('<?xml version="1.0" ?><phpdoc></phpdoc>'));
        $translator = $this->getMock('Zend\I18n\Translator\Translator');

        $plugin = $this->getMockForAbstractClass(
            'phpDocumentor\Plugin\PluginAbstract',
            array($dispatcher, $config, $translator)
        );

        $listener = $this->getMock('phpDocumentor\Plugin\ListenerAbstract', array(), array($plugin));
        $this->assertAttributeEquals($plugin, 'plugin', $listener);
        $this->assertAttributeEquals($dispatcher, 'event_dispatcher', $listener);
        $this->assertAttributeEquals($config, 'configuration', $listener);
        $this->assertAttributeEquals($config, 'translator', $listener);
    }
}
