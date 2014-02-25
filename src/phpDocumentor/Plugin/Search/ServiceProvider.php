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
use Guzzle\Http\Client;
use phpDocumentor\Plugin\Search\Writer\Search;
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
        $app->extend(
            'transformer.writer.collection',
            function ($app) {
                $writerCollection = $app['transformer.writer.collection'];
                $writerCollection['Search'] = new Search();
            }
        );

        $this->addLunrJsEngineAndSettings($app);
        $this->addElasticSearchEngineAndSettings($app);
    }

    /**
     * Adds the services and settings for the LunrJs Engine.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function addLunrJsEngineAndSettings(Application $app)
    {
        $app['search.engine.lunrjs.schema'] = array();
        $app['search.engine.lunrjs.path'] = '';
        $app['search.engine.lunrjs'] = $app->share(
            function ($app) {
                new Engine\LunrJs(
                    new Engine\Configuration\LunrJs(
                        $app['search.engine.lunrjs.schema'],
                        $app['search.engine.lunrjs.path']
                    )
                );
            }
        );
    }

    /**
     * Adds the services and settings for the ElasticSearch Engine.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function addElasticSearchEngineAndSettings(Application $app)
    {
        $app['search.engine.elasticsearch.uri'] = 'http://localhost:9200';
        $app['search.engine.elasticsearch'] = $app->share(
            function ($app) {
                new Engine\Elasticsearch(
                    new Engine\Configuration\Elasticsearch(new Client(), $app['search.engine.elasticsearch.uri'])
                );
            }
        );
    }
}
