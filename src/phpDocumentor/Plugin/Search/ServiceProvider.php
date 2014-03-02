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
use phpDocumentor\Plugin\Search\Adapter\AdapterInterface;
use phpDocumentor\Plugin\Search\Client\Generator;
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
        // generic configuration options
        $app['search.adapter'] = 'lunrjs';

        $this->addLunrJsEngineAndSettings($app);
        $this->addElasticSearchEngineAndSettings($app);
        $this->addEngineManager($app);
        $this->addClientGenerator($app);
        $this->appendSearchWriterToWriterCollection($app);
    }

    /**
     * Adds the services and settings for the LunrJs Adapter.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function addLunrJsEngineAndSettings(Application $app)
    {
        // lunrjs settings
        $app['search.adapter.lunrjs.schema'] = array();
        $app['search.adapter.lunrjs.path'] = '';
        
        // lunrjs adapter
        $app['search.adapter.lunrjs'] = $app->share(
            function ($app) {
                new Adapter\LunrJs(
                    new Adapter\Configuration\LunrJs(
                        $app['search.adapter.lunrjs.schema'],
                        $app['search.adapter.lunrjs.path']
                    )
                );
            }
        );
    }

    /**
     * Adds the services and settings for the ElasticSearch Adapter.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function addElasticSearchEngineAndSettings(Application $app)
    {
        // ElasticSearch settings
        $app['search.adapter.elasticsearch.uri'] = 'http://localhost:9200';

        // ElasticSearch adapter
        $app['search.adapter.elasticsearch'] = $app->share(
            function ($app) {
                new Adapter\Elasticsearch(
                    new Adapter\Configuration\Elasticsearch(new Client(), $app['search.adapter.elasticsearch.uri'])
                );
            }
        );
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    protected function addEngineManager(Application $app)
    {
        $app['search.engine.manager'] = $app->share(function ($app) {
            $adapterKey = 'search.adapter.' . $app['search.adapter'];
            $adapter = isset($app[$adapterKey]) ? $app[$adapterKey] : null;

            if (!$adapter instanceof AdapterInterface) {
                throw new \RuntimeException(
                    'The provided search adapter "' . $app['search.adapter'] . '" does not exist'
                );
            }

            return new EngineManager($adapter);
        });
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    protected function addClientGenerator(Application $app)
    {
        $app['search.client.generator'] = $app->share(function ($app) {
            if (!isset($app['twig'])) {
                throw new \RuntimeException(
                    'The Search Client Generator depends on Twig, make sure that twig is added to the container'
                );
            }

            return new Generator($app['twig']);
        });
    }

    /**
     * Appends the Search Writer to the Writer Collection of the transformer.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function appendSearchWriterToWriterCollection(Application $app)
    {
        $app->extend(
            'transformer.writer.collection',
            function ($app) {
                /** @var Collection $writerCollection */
                $writerCollection = $app['transformer.writer.collection'];
                $writerCollection['Search'] = new Search(
                    $app['search.engine.manager'],
                    $app['search.client.generator']
                );
            }
        );
    }
}
