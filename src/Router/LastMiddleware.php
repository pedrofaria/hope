<?php
namespace Hope\Router;

use Hope\Application;
use Hope\Contracts\MiddlewareInterface;
use Hope\Contracts\OutputerInterface;
use \Closure;

class LastMiddleware implements MiddlewareInterface
{
    private $outputer;
    
    public function __construct(Application $app, OutputerInterface $outputer)
    {
        $this->app = $app;
        $this->outputer = $outputer;
    }
    
    public function handle($request, Closure $next)
    {
        $responseData = $this->app->call($handler, $parameters);
        $this->outputer->buildResponse($responseData);

        return $this->outputer;
    }
}