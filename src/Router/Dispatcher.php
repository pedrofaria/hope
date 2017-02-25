<?php
namespace Hope\Router;

use \Hope\Application;
use \Hope\Exceptions\MethodNotAllowedException;
use \Hope\Exceptions\NotFoundException;
use \Hope\Http\Request;
use \Hope\Router\Router;

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
        try {
            $routeInfo = $this->dispatcher->dispatch(
                $this->request->getMethod(),
                $this->request->getUriForRouter()
            );

            $handler = $routeInfo->handler;
            $parameters = $routeInfo->variables;

            if ($routeInfo->data) {
                // @TODO check if there is request for middlewares
            }
            
            return $this->app->call($handler, $parameters);
        } catch (\FastRoute\Exception\HttpNotFoundException $e) {
            throw new NotFoundException;
        } catch (\FastRoute\Exception\HttpMethodNotAllowedException $e) {
            throw new MethodNotAllowedException;
        }
    }
}
