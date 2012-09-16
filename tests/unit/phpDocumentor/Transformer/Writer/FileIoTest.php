<?php
/**
 * Test class for FileIO writer.
 */

namespace phpDocumentor\Plugin\Core\Transformer\Writer;

class FileIoTest extends \PHPUnit_Framework_TestCase
{
    /** @var \phpDocumentor\Transformer\Transformer */
    public $transformer;

    /** @var FileIo */
    protected $fixture = null;

    protected function setUp()
    {
        $this->transformer = new \phpDocumentor\Transformer\Transformer();
        $this->fixture = new FileIo($this->transformer);
    }

    public function testExecuteQueryCopy()
    {
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
            $this->fixture->transform(new \DOMDocument(), $t);

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
            $this->fixture->transform(new \DOMDocument(), $t);

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

    public function testExecuteTransform()
    {
        touch('/tmp/phpdoc_a');
        @unlink('/tmp/phpdoc_b');
        $this->assertFileExists('/tmp/phpdoc_a');
        $this->assertFileNotExists('/tmp/phpdoc_b');

        $tr = new \phpDocumentor\Transformer\Transformer();
        $tr->setTarget('/tmp');

        try
        {
            $t = new \phpDocumentor\Transformer\Transformation(
                'FileIo', '/tmp/phpdoc_a', 'phpdoc_b', 'copyz'
            );
            $this->fixture->transform(new \DOMDocument(), $t);

            $this->fail('When un unknown query type is used an exception is expected');
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