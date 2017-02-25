<?php
namespace Hope;

use Hope\Router\RouteCollector;

/**
 * Router class
 *
 * @package Hope
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class Router
{
    private $dispatcher;
    private $app;

    /**
     * Constructor
     *
     * @param Application $app Hope Application.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register all routes to Hope
     *
     * @param string|Closure $routesDef Pass file name or a Closure of the Routes definitions
     *
     * @return void
     */
    public function register($routesDef)
    {
        $routeCollector = $this->getRouteCollector();
        
        $routesDef($routeCollector);

        $this->dispatcher = new \FastRoute\Dispatcher\GroupCountBased(
            $routeCollector->getData()
        );
    }

    /**
     * Create and get RouteCollector
     *
     * @return \FastRoute\RouteCollector Route Collector
     */
    public function getRouteCollector()
    {
        return new RouteCollector(
            new \FastRoute\RouteParser\Std,
            new \FastRoute\DataGenerator\GroupCountBased
        );
    }

    /**
     * Get the router dispatcher
     *
     * @return \FastRoute\Dispatcher\GroupCountBased FastRoute Dispatcher.
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }
}
