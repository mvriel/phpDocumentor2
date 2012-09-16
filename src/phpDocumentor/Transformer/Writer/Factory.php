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
namespace phpDocumentor\Transformer\Writer;

use phpDocumentor\Transformer\Transformer;

/**
 * Factory class for writers.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class Factory
{
    /** @var Transformer */
    protected $transformer;

    /** @var array[] */
    protected $writers = array();

    /**
     * Initializes the factory with the transformer.
     *
     * @param Transformer $transformer
     */
    public function __construct(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Returns the transformer that is associated with this factory.
     *
     * @return Transformer
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * Registers a class name to a given code.
     *
     * @param string $writer_name
     * @param string $class_name
     *
     * @return void
     */
    public function register($writer_name, $class_name)
    {
        $this->writers[$writer_name]['class_name'] = $class_name;
    }

    /**
     * Returns an instance of a Writer with the given name.
     *
     * Since writers are fly-weight objects this factory creates each only once
     * and caches that writer.
     *
     * @param string $writer_name
     *
     * @throws \InvalidArgumentException if the writer_name is not registered
     * @throws \InvalidArgumentException if the class associated with the
     *     writer_name does not extend WriterAbstract
     *
     * @return WriterAbstract
     */
    public function get($writer_name)
    {
        if (!isset($this->writers[$writer_name]['class_name'])) {
            throw new \InvalidArgumentException(
                'The writer '.$writer_name.' is not registered'
            );
        }

        if (!isset($this->writers[$writer_name]['object'])) {
            $class_name = $this->writers[$writer_name]['class_name'];

            $writer = new $class_name($this->transformer);
            if (!$writer instanceof WriterAbstract) {
                throw new \InvalidArgumentException(
                    'The class registered to writer '.$writer_name.' is not '
                    .'extended from WriterAbstract and might thus not be a '
                    .'real writer.'
                );
            }

            $this->writers[$writer_name]['object']
                = new $class_name($this->transformer);
        }

        return $this->writers[$writer_name]['object'];
    }
}
