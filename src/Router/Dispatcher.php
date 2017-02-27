<?php
namespace Hope\Router;

use Hope\Contracts\OutputerInterface;
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
    private $outputer;

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
        Router $router,
        OutputerInterface $outputer
    ) {
        $this->app = $app;
        $this->request = $request;
        $this->dispatcher = $router->getDispatcher();
        $this->outputer = $outputer;
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
            
            $responseData = $this->app->call($handler, $parameters);
            $response = $this->outputer->buildResponse($responseData);

            return $this->outputer;
        } catch (\FastRoute\Exception\HttpNotFoundException $e) {
            throw new NotFoundException;
        } catch (\FastRoute\Exception\HttpMethodNotAllowedException $e) {
            throw new MethodNotAllowedException;
        }
    }
}
