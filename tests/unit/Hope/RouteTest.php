<?php

namespace Hope;

use AspectMock\Test as test;
use Hope\Application;
use Hope\Router\Dispatcher;

class RouteTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $routers = [
            ['GET', '/test', function () { return 'test'; }],
            ['POST', '/test2/{id}', function ($id) { return 'test '.$id; }],
            ['GET', '/test3/{id:\d+}/{slug:[a-z0-9-]+}', function ($id, $slug) { return 'test '.$id .' - '.$slug; }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);
    }

    protected function _after()
    {
        test::clean(); // remove all registered test doubles
    }

    // tests
    public function testIfRoutesAreRegistered()
    {
        $app = new Application;
        $app->bootstrap();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $responseData = $app->call([Dispatcher::class, 'dispatch']);

        $this->assertEquals('test', $responseData);
    }

    public function testItShouldFailWithNotFoundIfInvalidURI()
    {
        $this->tester->expectException('Hope\Exceptions\NotFoundException', function () {
            $app = new Application;
            $app->bootstrap();
            $request = $app->get('Hope\Http\Request');
            $request->server->set('REQUEST_URI', '/other-test');
            $request->server->set('REQUEST_METHOD', 'POST');
            $responseData = $app->call([Dispatcher::class, 'dispatch']);
        });
    }

    public function testItShouldFailWithMethodNotAllowed()
    {
        $this->tester->expectException('Hope\Exceptions\MethodNotAllowedException', function () {
            $app = new Application;
            $app->bootstrap();
            $request = $app->get('Hope\Http\Request');
            $request->server->set('REQUEST_URI', '/test');
            $request->server->set('REQUEST_METHOD', 'DELETE');

            $responseData = $app->call([Dispatcher::class, 'dispatch']);
        });
    }

    public function testRouteShouldWorkWell()
    {
        $route = new \Hope\Route;

        $route->add('GET', '/test', 'Action1');
        $route->add('POST', '/test', 'Action2');
        $route->add('GET', '/test2', 'Action3');

        $this->assertCount(3, $route->getRoutes());
    }

    public function testValidParams()
    {
        $app = new Application;
        $app->bootstrap();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test2/2');
        $request->server->set('REQUEST_METHOD', 'POST');
        $responseData = $app->call([Dispatcher::class, 'dispatch']);

        $this->assertEquals('test 2', $responseData);
    }

    public function testValidTwoParams()
    {
        $app = new Application;
        $app->bootstrap();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test3/3/aaa-bbb');
        $request->server->set('REQUEST_METHOD', 'GET');
        $responseData = $app->call([Dispatcher::class, 'dispatch']);

        $this->assertEquals('test 3 - aaa-bbb', $responseData);
    }

    public function testInvalidParams()
    {
        $this->tester->expectException('Hope\Exceptions\NotFoundException', function () {
            $app = new Application;
            $app->bootstrap();
            $request = $app->get('Hope\Http\Request');
            $request->server->set('REQUEST_URI', '/test3/INVALID/aaa-bbb');
            $request->server->set('REQUEST_METHOD', 'GET');
            $responseData = $app->call([Dispatcher::class, 'dispatch']);
        });
    }
}
