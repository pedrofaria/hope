<?php
namespace Hope\Router;

use Hope\Application;
use Hope\Contracts\MiddlewareInterface;
use Hope\Contracts\OutputerInterface;
use \Closure;

class LastMiddleware implements MiddlewareInterface
{
    private $outputer;
    private $app;
    private $handler;
    private $parameters;
    
    public function __construct(Application $app, OutputerInterface $outputer)
    {
        $this->app = $app;
        $this->outputer = $outputer;
    }

    public function setRouteInfo($handler, $parameters)
    {
        $this->handler = $handler;
        $this->parameters = $parameters;
    }
    
    public function handle($request, Closure $next)
    {
        $responseData = $this->app->call($this->handler, $this->parameters);

        return $this->outputer->buildResponse($responseData);
    }
}