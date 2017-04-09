<?php

use AspectMock\Test as test;
use Hope\Application;
use Hope\Http\Controller;
use Hope\Router\Dispatcher;
use Hope\Router\RouteCollector;

class DummyController extends Controller
{}

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

    private function getBaseApp(\Closure $routes)
    {
        $app = new Application;
        $app->setRoute($routes);
        $app->bootstrap();

        return $app;
    }

    // tests
    public function testControllerShouldReturnAResponseObjIfResponseMethodIsCalled()
    {
        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function (DummyController $controller) { 
                return $controller->response('test', 201);
            });
        });
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $ouputer = $app->call([Dispatcher::class, 'dispatch']);
        $responseData = $ouputer->getResponse();

        $this->assertInstanceOf('Hope\Http\Response', $responseData);
        $this->assertEquals('test', $responseData->getContent());
        $this->assertEquals(201, $responseData->getStatusCode());
    }

    public function testControllerGetData()
    {
        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function (DummyController $controller) { 
                $data = [
                    'all' => $controller->getData(),
                    'foo' => $controller->getData('foo')
                ];
                return $data;
            });
        });

        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test?foo=bar');
        $request->server->set('REQUEST_METHOD', 'GET');
        $request->query->set('foo', 'bar');
        $ouputer = $app->call([Dispatcher::class, 'dispatch']);
        $responseData = $ouputer->getResponse();

        $data = '{"all":{"foo":"bar"},"foo":"bar"}';
        $this->assertEquals($data, $responseData->getContent());
    }

    public function testControllerPostData()
    {
        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function (DummyController $controller) { 
                $data = [
                    'all' => $controller->postData(),
                    'foo' => $controller->postData('foo')
                ];
                return $data;
            });
        });
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $request->request->set('foo', 'bar');
        $ouputer = $app->call([Dispatcher::class, 'dispatch']);
        $responseData = $ouputer->getResponse();

        $data = '{"all":{"foo":"bar"},"foo":"bar"}';
        $this->assertEquals($data, $responseData->getContent());
    }

    public function testSetCache()
    {
        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function (DummyController $controller) { 
                $controller->setCache(['etag' => 'aaAAaaBBbbBB']);
            });
        });
        $request = $app->get('Hope\Http\Request');
        $request->server->set('REQUEST_URI', '/test');
        $request->server->set('REQUEST_METHOD', 'GET');
        $ouputer = $app->call([Dispatcher::class, 'dispatch']);
        $response = $ouputer->getResponse();

        $response = $app->get('Hope\Http\Response');

        $this->assertEquals('"aaAAaaBBbbBB"', $response->getEtag());
    }
}