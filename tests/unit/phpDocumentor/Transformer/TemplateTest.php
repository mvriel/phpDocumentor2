<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Transformer;

/**
 * Test class for \phpDocumentor\Transformer\Template.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class TemplateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests the setting and getting of the name.
     *
     * @covers phpDocumentor\Transformer\Template::getName
     * @covers phpDocumentor\Transformer\Template::setName
     *
     * @return void
     */
    public function testSettingAName()
    {
        $template = new Template();
        $this->assertEquals('', $template->getName());

        $template->setName('test');

        $this->assertEquals('test', $template->getName());
    }

    /**
     * Tests the setting and getting of the name.
     *
     * @covers phpDocumentor\Transformer\Template::getAuthor
     * @covers phpDocumentor\Transformer\Template::setAuthor
     *
     * @return void
     */
    public function testSettingAnAuthor()
    {
        $template = new Template();
        $this->assertEquals('', $template->getAuthor());

        $template->setAuthor('Mike van Riel');

        $this->assertEquals('Mike van Riel', $template->getAuthor());
    }

    /**
     * Tests the setting and getting of the version.
     *
     * @covers phpDocumentor\Transformer\Template::getVersion
     * @covers phpDocumentor\Transformer\Template::setVersion
     *
     * @return void
     */
    public function testSettingTheVersion()
    {
        $template = new Template();
        $this->assertEquals('', $template->getVersion());

        $template->setVersion('2.1.3');

        $this->assertEquals('2.1.3', $template->getVersion());
    }

    /**
     * Tests the setting and getting of the version.
     *
     * @covers phpDocumentor\Transformer\Template::setVersion
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testSettingAnIncorrectVersion()
    {
        $template = new Template();
        $this->assertEquals('', $template->getVersion());

        $template->setVersion('a');
    }

    /**
     * Tests whether a transformation can successfully be added.
     *
     * @covers phpDocumentor\Transformer\Template::add
     *
     * @return void
     */
    public function testAddingATransformation()
    {
        $transformation = $this->getMock(
            'phpDocumentor\Transformer\Transformation', array(), array(), '',
            false
        );

        $template = new Template();
        $template[] = $transformation;

        $this->assertEquals(1, $template->count());
        $this->assertSame($transformation, $template[0]);

        $template->add($transformation);

        $this->assertEquals(2, $template->count());
        $this->assertSame($transformation, $template[1]);
    }
}
