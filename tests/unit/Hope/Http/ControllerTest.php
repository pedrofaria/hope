<?php

use AspectMock\Test as test;
use Hope\Application;
use Hope\Http\Controller;
use Hope\Router\Dispatcher;

class ControllerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _after()
    {
        test::clean(); // remove all registered test doubles
    }

    // tests
    public function testControllerShouldReturnAResponseObjIfResponseMethodIsCalled()
    {
        $routers = [
            ['GET', '/test', function (Controller $controller) { 
                return $controller->response('test', 201);
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);

        $app = new Application;
        $app->bootstrap();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $responseData = $app->call([Dispatcher::class, 'dispatch']);

        $this->assertInstanceOf('Hope\Http\Response', $responseData);
        $this->assertEquals('test', $responseData->getContent());
        $this->assertEquals(201, $responseData->getStatusCode());
    }

    public function testControllerGetData()
    {
        $routers = [
            ['GET', '/test', function (Controller $controller) { 
                $data = [
                    'all' => $controller->getData(),
                    'foo' => $controller->getData('foo')
                ];
                return $data;
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);

        $app = new Application;
        $app->bootstrap();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test?foo=bar');
        $request->server->set('REQUEST_METHOD', 'GET');
        $request->query->set('foo', 'bar');
        $responseData = $app->call([Dispatcher::class, 'dispatch']);

        $data = [
            'all' => ['foo' => 'bar'],
            'foo' => 'bar'
        ];
        $this->assertEquals($data, $responseData);
    }

    public function testControllerPostData()
    {
        $routers = [
            ['GET', '/test', function (Controller $controller) { 
                $data = [
                    'all' => $controller->postData(),
                    'foo' => $controller->postData('foo')
                ];
                return $data;
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);

        $app = new Application;
        $app->bootstrap();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $request->request->set('foo', 'bar');
        $responseData = $app->call([Dispatcher::class, 'dispatch']);

        $data = [
            'all' => ['foo' => 'bar'],
            'foo' => 'bar'
        ];
        $this->assertEquals($data, $responseData);
    }

    public function testSetCache()
    {
        $routers = [
            ['GET', '/test', function (Controller $controller) { 
                $controller->setCache(['etag' => 'aaAAaaBBbbBB']);
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);

        $app = new Application;
        $app->bootstrap();
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $responseData = $app->call([Dispatcher::class, 'dispatch']);

        $response = $app->get('Hope\Http\Response');

        $this->assertEquals('"aaAAaaBBbbBB"', $response->getEtag());
    }
}