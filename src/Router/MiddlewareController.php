<?php
namespace Hope\Router;

use Hope\Application;
use Hope\Contracts\MiddlewareInterface;

class MiddlewareController
{
    private $app;
    private $list = [];
    private $currentIndex = 0;
    private $lastMW;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function count()
    {
        return count($this->list);
    }

    public function add($middleware)
    {
        if (is_array($middleware)) {
            foreach ($middleware as $class) {
                $this->add($class);
            }
            return;
        }

        if (!in_array(MiddlewareInterface::class, class_implements($middleware))) {
            // @TODO create new class exception
            throw new \Exception('Invalid Middleware class');
        }

        $this->list[] = $middleware;
    }

    public function addLast($obj)
    {
        if (!$obj) {
            // @TODO create new class exception
            throw new \Exception('Invalid Middleware class');
        }

        $this->lastMW = $obj;
    }

    private function runNext($request)
    {
        if ($this->currentIndex < count($this->list) - 1) {
            $nextMW = function($request) {
                return $this->runNext($request);
            };
        }
        else {
            $nextMW = function($request) {
                return $this->runLast($request);
            };
        }
        $nextMW->bindTo($this);

        $current = $this->app->get($this->list[$this->currentIndex++]);
        return $current->handle($request, $nextMW);
    }

    private function runLast($request)
    {
        $last = $this->app->get($this->lastMW);
        return $last->handle($request, function() {});
    }

    public function run($request)
    {
        return $this->runNext($request);
    }
}