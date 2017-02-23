<?php
namespace Hope;

use Hope\Application;

/**
 * Route class
 *
 * @package Hope
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class Route
{
    private $routes = [];

    /**
     * Get all added routes
     *
     * @return array Route list
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add a new route
     *
     * @param string|array    $method Http method.
     * @param string          $uri    URI.
     * @param string|callable $action Action.
     *
     * @return void
     */
    public function add($method, string $uri, $action)
    {
        $this->routes[] = [$method, $uri, $action];
    }
}
