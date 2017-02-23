<?php
namespace Hope;

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
     * @return void
     */
    public function register()
    {
        $routeCollector = $this->getRouteCollector();

        $routes = $this->getConfiguredRoutes($routeCollector);

        foreach ($routes as $route) {
            $routeCollector->addRoute($route[0], $route[1], $route[2]);
        }

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
        return new \FastRoute\RouteCollector(
            new \FastRoute\RouteParser\Std,
            new \FastRoute\DataGenerator\GroupCountBased
        );
    }

    /**
     * Get Routes defined by config/routes.php file
     *
     * @param \FastRoute\RouteCollector $routeCollector Route Collector.
     *
     * @return array List of Routes.
     */
    private function getConfiguredRoutes(\FastRoute\RouteCollector $routeCollector)
    {
        $route = $this->app->get(Route::class);

        include_once $this->app->basePath() . '/config/routes.php';

        return $route->getRoutes();
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
