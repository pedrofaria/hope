<?php
namespace Hope\Router;

use Hope\Application;

/**
 * Route class
 *
 * @package Hope
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class RouteCollector extends \Router\RouteCollector
{
    /**
     * Add a new route
     *
     * @param string|array    $method  Http method.
     * @param string          $uri     URI.
     * @param string|callable $handler Action.
     *
     * @return void
     */
    public function add($method, string $uri, $handler, array $data = [])
    {
        $this->addRoute($method, $uri, $handler, $data);
    }
}
