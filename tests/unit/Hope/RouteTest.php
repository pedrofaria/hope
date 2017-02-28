<?php

namespace Hope;

use AspectMock\Test as test;
use Hope\Application;
use Hope\Router\Dispatcher;
use Hope\Router\RouteCollector;

class RouteTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $routes;

    protected function _before()
    {
        $this->routes = function(RouteCollector $route) {
            $route->add('GET', '/test', function () { return 'test'; });
            $route->add('POST', '/test2/{id}', function ($id) { return 'test '.$id; });
            $route->add('GET', '/test3/{id:\d+}/{slug:[a-z0-9-]+}', function ($id, $slug) { return 'test '.$id .' - '.$slug; });
        };
    }

    protected function _after()
    {
        test::clean(); // remove all registered test doubles
    }

    private function getBaseApp()
    {
        $app = new Application;
        $app->setRoute($this->routes);
        $app->bootstrap();

        return $app;
    }

    // tests
    public function testIfRoutesAreRegistered()
    {
        $app = $this->getBaseApp();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $app->run();

        $this->expectOutputString('test');
    }

    public function testItShouldFailWithNotFoundIfInvalidURI()
    {
        $this->tester->expectException('Hope\Exceptions\NotFoundException', function () {
            $app = $this->getBaseApp();
            $request = $app->get('Hope\Http\Request');
            $request->server->set('REQUEST_URI', '/other-test');
            $request->server->set('REQUEST_METHOD', 'POST');
            $app->call([Dispatcher::class, 'dispatch']);
        });
    }

    public function testItShouldFailWithMethodNotAllowed()
    {
        $this->tester->expectException('Hope\Exceptions\MethodNotAllowedException', function () {
            $app = $this->getBaseApp();
            $request = $app->get('Hope\Http\Request');
            $request->server->set('REQUEST_URI', '/test');
            $request->server->set('REQUEST_METHOD', 'DELETE');

            $app->call([Dispatcher::class, 'dispatch']);
        });
    }

    public function testValidParams()
    {
        $app = $this->getBaseApp();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test2/2');
        $request->server->set('REQUEST_METHOD', 'POST');
        $app->call([Dispatcher::class, 'dispatch']);

        $this->expectOutputString('test 2');
    }

    public function testValidTwoParams()
    {
        $app = $this->getBaseApp();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test3/3/aaa-bbb');
        $request->server->set('REQUEST_METHOD', 'GET');
        $app->call([Dispatcher::class, 'dispatch']);

        $this->expectOutputString('test 3 - aaa-bbb');
    }

    public function testInvalidParams()
    {
        $this->tester->expectException('Hope\Exceptions\NotFoundException', function () {
            $app = $this->getBaseApp();
            $request = $app->get('Hope\Http\Request');
            $request->server->set('REQUEST_URI', '/test3/INVALID/aaa-bbb');
            $request->server->set('REQUEST_METHOD', 'GET');
            $app->call([Dispatcher::class, 'dispatch']);
        });
    }
}
