<?php
namespace Hope\Router;

use \Hope\Application;
use \Hope\Exceptions\MethodNotAllowedException;
use \Hope\Exceptions\NotFoundException;
use \Hope\Http\Request;
use \Hope\Router;

/**
 * Router Dispatcher class
 *
 * @package App\Controllers
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class Dispatcher
{
    private $app;
    private $request;
    private $dispatcher;
    private $resolver;

    /**
     * Constructor
     *
     * @param Application $app     Hope Application.
     * @param Request     $request Request.
     * @param Router      $router  Router.
     */
    public function __construct(
        Application $app,
        Request $request,
        Router $router
    ) {
        $this->app = $app;
        $this->request = $request;
        $this->dispatcher = $router->getDispatcher();
    }

    /**
     * Execute the dispatcher
     *
     * @return mixed Action response data.
     *
     * @throws NotFoundException Page Not Found.
     * @throws MethodNotAllowedException Method Not Allowed.
     */
    public function dispatch()
    {
        $routeInfo = $this->dispatcher->dispatch(
            $this->request->getMethod(),
            $this->request->getUriForRouter()
        );

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new NotFoundException;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException;
        }

        $handler = $routeInfo[1];
        $parameters = $routeInfo[2];
        return $this->app->call($handler, $parameters);
    }
}
