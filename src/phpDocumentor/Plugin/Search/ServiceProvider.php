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

namespace phpDocumentor\Plugin\Search;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use phpDocumentor\Translator;
use phpDocumentor\Plugin\Core\Transformer\Writer;
use phpDocumentor\Transformer\Writer\Collection;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * @param Application $app An Application instance.
     */
    public function register(Application $app)
    {
        /** @var Collection $writerCollection */
        $writerCollection = $app['transformer.writer.collection'];

//        $writerCollection['Search']     = new Writer\Search();
    }
}
