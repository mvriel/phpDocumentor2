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
namespace phpDocumentor\Transformer\Template\Reader;

use phpDocumentor\Transformer\Template;

/**
 * Object capable of processing a string and populating a template.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
abstract class BaseReader
{
    /** @var Template|null */
    protected $template = null;

    /**
     * Initializes the read with the template object.
     *
     * @param Template $template
     */
    public function __construct(Template $template)
    {
        $this->setTemplate($template);
    }

    /**
     * Sets the template associated with this reader.
     *
     * @param Template $template
     *
     * @return BaseReader
     */
    protected function setTemplate(Template $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Returns the template associated with this reader.
     *
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Populates the template with the processed contents and returns it.
     *
     * @param Transformer $transformer
     * @param string      $body_text
     *
     * @return Template
     */
    abstract public function process($body_text);
}