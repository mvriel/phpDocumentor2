<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2014 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Plugin\Scrybe\Converter\RestructuredText\Directives;

use phpDocumentor\Plugin\Scrybe\Converter\RestructuredText\Visitors\Discover;

/**
 * Directive used to process `.. toctree::` and insert entries from the table of contents.
 *
 * This directive tries to match the file with an entry in the table of contents during the creation phase. If a
 * document is found it will generate a mini-table of contents at that location with the depth given using the
 * `:maxdepth:` parameter.
 *
 * Another option is :hidden: that will hide the toc from view while still making connections.
 *
 * This directive is inspired by {@link http://sphinx.pocoo.org/concepts.html#the-toc-tree Sphinx' toctree} directive.
 */
class Toctree extends \ezcDocumentRstDirective implements \ezcDocumentRstXhtmlDirective
{
    protected $links = array();

    public function __construct(\ezcDocumentRstDocumentNode $ast, $path, \ezcDocumentRstDirectiveNode $node)
    {
        parent::__construct($ast, $path, $node);

        $this->parseLinks();
    }

    protected function parseLinks()
    {
        $line = '';

        /** @var \ezcDocumentRstToken $token */
        foreach ($this->node->tokens as $token) {
            if ($token->type === 2) {
                $line = trim($line);
                if ($line) {
                    $this->links[] = $line;
                    $line = '';
                }
            }

            if ($token->type !== 5 && $token->type !== 4) {
                continue;
            }

            $line .= $token->content;
        }
    }

    /**
     * Transform directive to docbook
     *
     * Create a docbook XML structure at the directives position in the document.
     *
     * @param \DOMDocument $document
     * @param \DOMElement  $root
     *
     * @return void
     */
    public function toDocbook(\DOMDocument $document, \DOMElement $root)
    {
    }

    /**
     * Transform directive to HTML
     *
     * Create a XHTML structure at the directives position in the document.
     *
     * @param \DOMDocument $document
     * @param \DOMElement $root
     *
     * @todo use the TableofContents collection to extract a sublisting up to the given depth or 2 if none is provided
     *
     * @return void
     */
    public function toXhtml(\DOMDocument $document, \DOMElement $root)
    {
        $this->addLinksToTableOfContents();

        // if the hidden flag is set then this item should not be rendered but still processed (see above)
        if (isset($this->node->options['hidden'])) {
            return;
        }

        $list = $document->createElement('ol');
        $root->appendChild($list);

        foreach ($this->links as $link) {
            $list_item = $document->createElement('li');
            $list->appendChild($list_item);

            $link_element = $document->createElement('a');
            $list_item->appendChild($link_element);
            $link_element->appendChild($document->createTextNode($this->getCaption($link)));
            $link_element->setAttribute('href', str_replace('\\', '/', $link) . '.html');
        }
    }

    protected function addLinksToTableOfContents()
    {
        if (!$this->visitor instanceof Discover) {
            return;
        }

        $toc = $this->visitor->getTableOfContents();
        foreach ($this->links as $fileName) {
            $this->visitor->addFileToLastHeading(
                $toc[$this->getNormalizedFileName($this->visitor, $fileName)]
            );
        }
    }

    /**
     * Retrieves the caption for the given $token.
     *
     * The caption is retrieved by converting the filename to a human-readable format.
     *
     * @param string $fileName
     *
     * @return string
     */
    protected function getCaption($fileName)
    {
        $toc = $this->visitor->getTableOfContents();
        $normalizedFileName = $this->getNormalizedFileName($this->visitor, $fileName);
        var_dump('a: ' . $fileName, $normalizedFileName); # the headings are not read correctly
        $name = $toc[$normalizedFileName]->getName();

        return $name ? $name : $fileName;
    }

    /**
     * @param $visitor
     * @param $fileName
     * @return string
     */
    protected function getNormalizedFileName($visitor, $fileName)
    {
        if (!$visitor instanceof Discover) {
            return $fileName;
        }

        $fileName = dirname($visitor->getFilenameWithoutExtension()) . '/' . $fileName;
        if (substr($fileName, 0, 2) == './') {
            $fileName = substr($fileName, 2);
            return $fileName;
        }

        return $fileName;
    }
}
