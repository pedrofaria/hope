<?php

use AspectMock\Test as test;
use Hope\Application;
use Hope\Http\Response;
use Hope\Router\RouteCollector;

class OutputerJsonTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

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
    public function testItShouldGenerateACorrectJsonFromArray()
    {
        $app = $this->getBaseApp(function() {});

        $response = $app->call('Hope\Outputer\OutputerJson::buildResponse', ['responseData' => ['foo' => 'bar']]);
        $this->assertEquals('{"foo":"bar"}', $response->getContent());
    }

    public function testItShouldGenerateACorrectJsonFromResponseObject()
    {
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test'
            ]
        );

        $this->expectOutputString('{"test":"testValue"}');

        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function(Response $response) {
                return $response->easyResponse(['test' => 'testValue'], 201);
            });
        });
        $app->run();

        $response = $app->get('Hope\Http\Response');
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testItShouldGenerateACorrectJsonFromResponseObjectWithStringContent()
    {
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test'
            ]
        );

        $this->expectOutputString('foobar');

        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function(Response $response) {
                return $response->easyResponse('foobar');
            });
        });
        $app->run();

        $response = $app->get('Hope\Http\Response');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testItShouldGenerateACorrectJsonFromHttpException()
    {
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test2'
            ]
        );

        $this->expectOutputString('{"code":404,"message":"Page not Found"}');

        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function() {
                return '';
            });
        });
        $app->run();
    }

    public function testItShouldGenerateACorrectJsonException()
    {
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test'
            ]
        );

        $this->expectOutputString('{"code":1001,"message":"Error"}');

        $app = $this->getBaseApp(function(RouteCollector $r) {
            $r->add('GET', '/test', function() {
                throw new Exception('Error', '1001');
            });
        });
        $app->run();
    }
}