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

namespace phpDocumentor\Transformer\Template\Reader;

use phpDocumentor\Transformer\Template;

/**
 * Test class for \phpDocumentor\Transformer\Template\Reader\Xml.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class XmlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests whether a basic configuration string can be parsed.
     *
     * @covers phpDocumentor\Transformer\Template\Reader\Xml::process
     *
     * @return void
     */
    public function testProcessConfigurationString()
    {
        $template = $this->getMock(
            'phpDocumentor\Transformer\Template',
            array('setAuthor', 'setVersion', 'setName')
        );
        $template->expects($this->once())->method('setAuthor')
            ->with('Mike van Riel');
        $template->expects($this->once())->method('setVersion')->with('1.0.0');
        $template->expects($this->never())->method('setName');

        $fixture = <<<XML
<?xml version="1.0" encoding="UTF-8"?>

<template>
  <author>Mike van Riel</author>
  <email>mike.vanriel@naenius.com</email>
  <version>1.0.0</version>
  <copyright>Mike van Riel 2012</copyright>
</template>
XML;

        $xml = new Xml($template);
        $xml->process($fixture);
    }

    /**
     * Tests whether a basic configuration string can be parsed.
     *
     * @covers phpDocumentor\Transformer\Template\Reader\Xml::process
     *
     * @return void
     */
    public function testProcessConfigurationStringWithEmptyTransformations()
    {
        $template = $this->getMock(
            'phpDocumentor\Transformer\Template',
            array('setAuthor', 'setVersion', 'setName')
        );
        $template->expects($this->never())->method('add');

        $fixture = <<<XML
<?xml version="1.0" encoding="UTF-8"?>

<template>
  <author>Mike van Riel</author>
  <email>mike.vanriel@naenius.com</email>
  <version>1.0.0</version>
  <copyright>Mike van Riel 2012</copyright>
  <transformations>
  </transformations>
</template>
XML;

        $xml = new Xml($template);
        $xml->process($fixture);

        $this->assertCount(0, $template);
    }

    /**
     * Tests whether a basic configuration string can be parsed.
     *
     * @covers phpDocumentor\Transformer\Template\Reader\Xml::process
     *
     * @return void
     */
    public function testProcessConfigurationStringWithASingleTransformation()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Template $template  */
        $template = $this->getMock(
            'phpDocumentor\Transformer\Template',
            array('setAuthor', 'setVersion', 'setName')
        );
        $template->expects($this->never())->method('add');

        $fixture = <<<XML
<?xml version="1.0" encoding="UTF-8"?>

<template>
  <author>Mike van Riel</author>
  <email>mike.vanriel@naenius.com</email>
  <version>1.0.0</version>
  <copyright>Mike van Riel 2012</copyright>
  <transformations>
    <transformation query="copy" writer="FileIo" source="images" artifact="img"/>
  </transformations>
</template>
XML;

        $xml = new Xml($template);
        $xml->process($fixture);

        $this->assertCount(1, $template);
    }

    /**
     * Tests whether a basic configuration string can be parsed.
     *
     * @covers phpDocumentor\Transformer\Template\Reader\Xml::process
     *
     * @return void
     */
    public function testProcessConfigurationStringWithMultipleTransformations()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Template $template  */
        $template = $this->getMock(
            'phpDocumentor\Transformer\Template',
            array('setAuthor', 'setVersion', 'setName')
        );
        $template->expects($this->never())->method('add');

        $fixture = <<<XML
<?xml version="1.0" encoding="UTF-8"?>

<template>
  <author>Mike van Riel</author>
  <email>mike.vanriel@naenius.com</email>
  <version>1.0.0</version>
  <copyright>Mike van Riel 2012</copyright>
  <transformations>
    <transformation query="copy" writer="FileIo" source="images" artifact="img"/>
    <transformation query="copy" writer="FileIo" source="images" artifact="img"/>
  </transformations>
</template>
XML;

        $xml = new Xml($template);
        $xml->process($fixture);

        $this->assertCount(2, $template);
    }

    /**
     * Tests whether a basic configuration string can be parsed.
     *
     * @covers phpDocumentor\Transformer\Template\Reader\Xml::process
     *
     * @return void
     */
    public function testProcessConfigurationStringWithAParameter()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Template $template  */
        $template = $this->getMock(
            'phpDocumentor\Transformer\Template',
            array('setAuthor', 'setVersion', 'setName')
        );
        $template->expects($this->never())->method('add');

        $fixture = <<<XML
<?xml version="1.0" encoding="UTF-8"?>

<template>
  <author>Mike van Riel</author>
  <email>mike.vanriel@naenius.com</email>
  <version>1.0.0</version>
  <copyright>Mike van Riel 2012</copyright>
  <parameters>
    <my>parameter</my>
  </parameters>
  <transformations>
    <transformation query="copy" writer="FileIo" source="images" artifact="img"/>
  </transformations>
</template>
XML;

        $xml = new Xml($template);
        $xml->process($fixture);

        $this->assertCount(1, $template);

        $this->assertEquals(
            array('my' => 'parameter'),
            $template->getParameters()
        );
        $this->assertEquals(
            array('my' => 'parameter'),
            $template[0]->getParameters()
        );
    }
}