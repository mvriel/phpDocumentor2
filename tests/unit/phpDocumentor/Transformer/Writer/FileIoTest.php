<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2012 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Plugin\Core\Transformer\Writer;

/**
 * Tests whether the File IO writer properly copies the given source files.
 */
class FileIoTest extends \PHPUnit_Framework_TestCase
{
    /** @var \phpDocumentor\Transformer\Transformer */
    public $transformer;

    /** @var FileIo */
    protected $fixture = null;

    /**
     * Creates a new FileIO fixture.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->transformer = new \phpDocumentor\Transformer\Transformer();
        $this->fixture = new FileIo($this->transformer);
    }

    /**
     * Executes whether the query 'copy' is properly executed in a transformation.
     *
     * @covers phpDocumentor\Plugin\Core\Transformer\Writer\FileIO::transform
     *
     * @throws \Exception|\PHPUnit_Framework_AssertionFailedError
     *
     * @return void
     */
    public function testExecuteQueryCopy()
    {
        $this->markTestSkipped(
            'Test throws an undetermined error, should investigate'
        );

        touch('/tmp/phpdoc_a');
        @unlink('/tmp/phpdoc_b');
        $this->assertFileExists('/tmp/phpdoc_a');
        $this->assertFileNotExists('/tmp/phpdoc_b');

        $this->transformer->setTarget('/tmp');
        try
        {
            $t = new \phpDocumentor\Transformer\Transformation(
                'FileIo', '/tmp/phpdoc_b', 'phpdoc_c', 'copy'
            );
            $this->fixture->transform(new \DOMDocument(), $transformation);

            $this->fail(
                'When a non-existing source is provided, an exception is expected'
            );
        }
        catch (\PHPUnit_Framework_AssertionFailedError $e)
        {
            throw $e;
        }
        catch (\Exception $e)
        {
            // this is good
        }

        try
        {
            $this->transformer->setTarget('/tmpz');
            $t = new \phpDocumentor\Transformer\Transformation(
                $this->transformer, 'copy', 'FileIo', '/tmp/phpdoc_a', 'phpdoc_b'
            );
            $this->fixture->transform(new \DOMDocument(), $transformation);

            $this->fail(
                'When a non-existing transformer target is provided, '
                . 'an exception is expected'
            );
        }
        catch (\PHPUnit_Framework_AssertionFailedError $e)
        {
            throw $e;
        }
        catch (\Exception $e)
        {
            // this is good
        }

        $this->markTestIncomplete(
            'Absolute files are no longer supported using the FileIo writer, '
            .'the test code should be adapted'
        );

        unlink('/tmp/phpdoc_a');
        unlink('/tmp/phpdoc_b');
    }

    /**
     * Executes whether the query 'copy' is properly executed in a transformation.
     *
     * @covers phpDocumentor\Plugin\Core\Transformer\Writer\FileIO::transform
     *
     * @throws \Exception|\PHPUnit_Framework_AssertionFailedError
     *
     * @return void
     */
    public function testExecuteTransform()
    {
        touch('/tmp/phpdoc_a');
        @unlink('/tmp/phpdoc_b');
        $this->assertFileExists('/tmp/phpdoc_a');
        $this->assertFileNotExists('/tmp/phpdoc_b');

        $transformer = new \phpDocumentor\Transformer\Transformer();
        $transformer->setTarget('/tmp');

        try
        {
            $t = new \phpDocumentor\Transformer\Transformation(
                'FileIo', '/tmp/phpdoc_a', 'phpdoc_b', 'copyz'
            );
            $this->fixture->transform(new \DOMDocument(), $t);

            $this->fail(
                'When un unknown query type is used an exception is expected'
            );
        }
        catch (\InvalidArgumentException $e)
        {
            // this is good
        }

        $this->markTestIncomplete(
            'Absolute files are no longer supported using the FileIo writer, '
            .'the test code should be adapted'
        );

        unlink('/tmp/phpdoc_a');
        unlink('/tmp/phpdoc_b');
    }
}